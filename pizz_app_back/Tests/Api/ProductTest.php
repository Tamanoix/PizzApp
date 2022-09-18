<?php

// api/tests/BooksTest.php
namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Product;


class ProductTest extends ApiTestCase
{
    /*// This trait provided by AliceBundle will take care of refreshing the database content to a known state before each test
    use RefreshDatabaseTrait;*/

    public function testGetCollection(): void
    {
        // The client implements Symfony HttpClient's `HttpClientInterface`, and the response `ResponseInterface`
        $response = static::createClient()->request('GET', '/products');

        $this->assertResponseIsSuccessful();

         $expectedResponseJson = '[
    {
        "name": "pizza 4 fromages",
        "description": "Ducimus qui fugit consequatur ab reiciendis. Inventore deserunt alias id iure reprehenderit debitis soluta. Ullam aut animi dolorem alias.",
        "price": 9,
        "category": {
        "name": "pizza"
        }
    },
    {
        "name": "pizza napolitaine",
        "description": "Sequi deleniti aut rerum eos. Provident expedita quia dicta quis in. Dolores et quis est vel hic culpa velit. Molestiae consectetur in aliquid repellendus adipisci temporibus beatae expedita.",
        "price": 4,
        "category": {
        "name": "pizza"
        }
    },
    {
        "name": "pizza royale",
        "description": "Quis aut illum ea iste deleniti sit mollitia. Aut placeat iure ut ea. Rem at sunt itaque nisi occaecati est. Aperiam in nihil praesentium consequatur.",
        "price": 7,
        "category": {
        "name": "pizza"
        }
    },
    {
        "name": "pizza anchois",
        "description": "Sit asperiores odio minus delectus. Cumque id excepturi natus similique.",
        "price": 8,
        "category": {
        "name": "pizza"
        }
    },
    {
        "name": "calzone 4 fromages",
        "description": "Provident iusto aut porro sed delectus eum. Et labore veritatis amet sint reiciendis. Nisi quisquam maxime quia quos enim earum. Odit quibusdam omnis omnis similique sunt.",
        "price": 2,
        "category": {
        "name": "calzone"
        }
    },
    {
        "name": "calzone napolitaine",
        "description": "Qui modi voluptatem perspiciatis id voluptate et. Maxime dolorem labore officiis in. Eum quisquam omnis laboriosam consequatur.",
        "price": 7,
        "category": {
        "name": "calzone"
        }
    },
    {
        "name": "calzone royale",
        "description": "Cupiditate id maiores odio nulla inventore est consequatur. Cum aliquid ex aut et deserunt. Eos dicta ut explicabo rerum veniam quia.",
        "price": 11,
        "category": {
        "name": "calzone"
        }
    },
    {
        "name": "calzone anchois",
        "description": "Dolor nihil voluptatum autem distinctio velit. Qui laboriosam laborum reprehenderit ut sit alias. Voluptate mollitia aut voluptates vitae rem.",
        "price": 8,
        "category": {
        "name": "calzone"
        }
    },
    {
        "name": "Coca Kola",
        "description": "Non veniam quo tempora ipsam iusto fugiat. Tempore praesentium id facere minus sit repudiandae ad fuga. Rerum assumenda unde ad ut impedit.",
        "price": 3,
        "category": {
        "name": "boisson"
        }
    },
    {
        "name": "Vin rouge",
        "description": "Autem velit amet impedit deserunt et sunt. Impedit eveniet dolor ipsam non. Aut dicta possimus vel consequatur quia.",
        "price": 7,
        "category": {
        "name": "boisson"
        }
    },
    {
        "name": "Jus de fruit",
        "description": "Sit repellat repellendus assumenda sit non. Odio sed voluptas deserunt rem minima eos. Est voluptatum velit aut qui deserunt debitis enim harum.",
        "price": 2,
        "category": {
        "name": "boisson"
        }
    },
    {
        "name": "Eau",
        "description": "Voluptatem nisi tempora minima cumque. Asperiores voluptas eum perspiciatis dolor et. Dolorem iste est sint blanditiis sapiente mollitia. Odit quidem eveniet pariatur.",
        "price": 11,
        "category": {
        "name": "boisson"
        }
    }
]';

        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');


        // Asserts that the returned JSON is a superset of this one
        $this->assertJsonContains($expectedResponseJson
        );

        // Because test fixtures are automatically loaded between each test, you can assert on them
       // $this->assertCount(10, $response->toArray()['hydra:member']);

        // Asserts that the returned JSON is validated by the JSON Schema generated for this resource by API Platform
        // This generated JSON Schema is also used in the OpenAPI spec!
       // $this->assertMatchesResourceCollectionJsonSchema(Product::class);
    }

}