<?php

namespace Tests;

use App\Models\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;

trait GenericControllerTestTrait
{
   // use RefreshDatabase;

    private $base_url = '/api/v1';

    abstract protected function getInstance();
    abstract protected function getUser();
    abstract protected function getObject();
    abstract protected function getModel();

    public function test_index(): void
    {
        $index_route = $this->getIndexRoute() ? $this->getIndexRoute() : $this->base_url . "/{$this->getInstance()}s";
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken()])
            ->get($index_route);

        $response->assertStatus(200);
    }

    public function test_store(): void
    {
        $object = $this->getObject();

        $store_route = $this->getStoreRoute() ? $this->getStoreRoute() : $this->base_url . "/{$this->getInstance()}/create";

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken()])
            ->post($store_route, $object);

        $response->assertStatus(201);
    }

    public function test_update(): void
    {
        $object = $this->getObject();
        $row = $this->getModel()::inRandomOrder()->first();
        $route = $this->getUpdateRoute() ? $this->getUpdateRoute() : $this->base_url . "/{$this->getInstance()}";
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken()])
            ->put("{$route}/{$row->uuid}", $object);
        $response->assertStatus(200);
    }

    public function test_show(): void
    {
        $row = $this->getModel()::inRandomOrder()->first();
        $route = $this->getShowRoute() ? $this->getShowRoute() : $this->base_url . "/{$this->getInstance()}";
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken()])
            ->get("{$route}/{$row->uuid}");
        $response->assertStatus(200);
    }

    public function test_delete(): void
    {
        $row = $this->getModel()::inRandomOrder()->first();
        $route = $this->getDeleteRoute() ? $this->getDeleteRoute() : $this->base_url . "/{$this->getInstance()}";
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken()])
            ->delete("{$route}/{$row->uuid}");
        $response->assertStatus(204);
    }

    private function authenticate()
    {
        $login_route = $this->getLoginRoute() ? $this->getLoginRoute() : $this->base_url . "/login";
        $response = $this->postJson($login_route, $this->getUser());
        return  $response;
    }

    private function getToken()
    {
        return $this->authenticate()->json()['data']['token'] ?? null;
    }

    public function getLoginRoute()
    {
        return null;
    }

    public function getIndexRoute()
    {
        return null;
    }

    public function getStoreRoute()
    {
        return null;
    }

    public function getUpdateRoute()
    {
        return null;
    }
    public function getShowRoute()
    {
        return null;
    }
    public function getDeleteRoute()
    {
        return null;
    }
}
