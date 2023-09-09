<?php

namespace App\Services;

use App\Models\JwtToken;
use App\Models\User;
use Illuminate\Support\Str;
use DateTimeImmutable;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Illuminate\Http\Request;

class JwtService
{
    private $sever_domain;
    private $config;
    private $key;

    public function __construct()
    {
        $this->sever_domain =  config('app.APP_URL');
        $this->key =  config('app.PRIVATE_KEY');
        $verification_key =  config('app.VERIFICATION_KEY');

        $this->config = Configuration::forAsymmetricSigner(
            new Sha256(),
            InMemory::plainText($this->key),
            InMemory::base64Encoded($verification_key)
        );
    }

    public function issueToken(User $user)
    {
        $identifier = Str::uuid();
        $now   = new DateTimeImmutable();

        $builder = $this->config->builder();

        $token =  $builder->issuedBy($this->sever_domain)
            ->expiresAt($now->modify('+1 hour'))
            ->identifiedBy($identifier)
            ->withClaim('uid', $user->uuid)
            ->getToken($this->config->signer(), $this->config->signingKey());

        $user->jwt_tokens()->firstOrCreate([
            'unique_id' => $identifier,
            'token_title' => 'Token for ' . $user->uuid,
            'created_at' => now(),
            'updated_at' => now(),
            'expires_at' => now()->addMinutes(60), // Replace with the desired expiration time
            'last_used_at' => null, // You can set this to null initially
            'refreshed_at' => null, // You can set this to null initially
        ]);

        return $token;
    }

    public function validate($bearerToken,$isAdmin = false)
    {
        $result = false ;
        $parser = new Parser(new JoseEncoder());

        $token = $parser->parse($bearerToken);

        //check if token is valid
        if ( $this->config->validator()->validate(
            $token,
            new SignedWith( $this->config->signer(),  $this->config->signingKey()),
            new IssuedBy($this->sever_domain)
        )) 
        {

            $row = JwtToken::where('unique_id',$token->claims()->get('jti'))->firstOrFail();

  
            //check if token exists and its not expired and its for this user
            if($row && 
               $row->expires_at > now() &&
               $token->claims()->get('uid') == $row->user?->uuid && 
               $row->user?->is_admin == $isAdmin )
            {
                $result = true;
            }
            
        }

        return $result;
    }

    public function extractBearerToken(Request $request)
    {
        if ($request->headers->has('Authorization') && 
        strpos($request->headers->get('Authorization'),'Bearer') === 0) 
        {
            return substr($request->headers->get('Authorization'), 7);
        }

        return null;
    }

    public function getUserFromBearer($bearerToken)
    {
        $parser = new Parser(new JoseEncoder());

        $token = $parser->parse($bearerToken);

        if($uuid = $token->claims()?->get('uid'))
        {
            return User::where('uuid',$uuid)->firstOrFail();
        }
        return null;
    }
}
