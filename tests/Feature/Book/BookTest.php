<?php

namespace Tests\Feature\Book;

use App\Models\Book;
use App\Models\Summary;
use Database\Factories\BookFactory;
use Database\Factories\SummaryFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    private function recursiveAssert($array){
        $this->assertDatabaseHas('summaries', [
            'title' => $array['title'],
            'page' => $array['page'],
        ]);

        foreach ($array['subsummaries'] as $value) {
            if (is_array($value)) {
                $this->recursiveAssert($value);
            }
        }
    }

    public function test_it_should_be_create_book_and_summaries_and_subsummaries()
    {
        //arrange
        $jsonData = '{"title":"exemplo","summaries":[{"title":"indice 1","page":2,"subsummaries":[{"title":"indice 1.1","page":2,"subsummaries":[{"title":"indice 1.1.1","page":2,"subsummaries":[]}]}]},{"title":"indice 2","page":2,"subsummaries":[]}]}';
        $arrayData = json_decode($jsonData, true);
        $password = \Str::random(10);
        $user = UserFactory::new()->create(['password' => $password]);
        $token = $this->post('/v1/auth/token', [
            'email' => $user->email,
            'password' => $password
        ])->json()['data']['token'];

        //act
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->post('/v1/books', $arrayData);

        //assert
        $response->assertSuccessful();
        $this->assertDatabaseHas('books', [
            'title' => $arrayData['title'],
            'user_id' => \Auth::user()->id
        ]);

        foreach ($arrayData['summaries'] as $summary){
            foreach ($summary['subsummaries'] as $subsummary){
                $this->recursiveAssert($subsummary);
            }

            $this->assertDatabaseHas('summaries', [
                'title' => $summary['title'],
                'page' => $summary['page'],
            ]);
        }
    }

    public function test_fail_authentication()
    {
        //arrange
        $jsonData = '{"title":"exemplo","summaries":[{"title":"indice 1","page":2,"subsummaries":[{"title":"indice 1.1","page":2,"subsummaries":[{"title":"indice 1.1.1","page":2,"subsummaries":[]}]}]},{"title":"indice 2","page":2,"subsummaries":[]}]}';
        $arrayData = json_decode($jsonData, true);
        $password = \Str::random(10);
        $user = UserFactory::new()->create(['password' => $password]);
        $token = $this->post('/v1/auth/token', [
            'email' => $user->email,
            'password' => $password
        ])->json()['data']['token']. 'FAILTOKEN';

        //act
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->post('/v1/books', $arrayData);

        //assert
        $response->assertFound();
        $this->assertNull(\Auth::user());
    }

    public function test_it_should_be_return_book()
    {
        //arrange
        $password = \Str::random(10);
        $user = UserFactory::new()->create(['password' => $password]);
        $token = $this->post('/v1/auth/token', [
            'email' => $user->email,
            'password' => $password
        ])->json()['data']['token'];
        $count = BookFactory::new()->count(10)->create();

        //act
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->get('/v1/books');

        //assert
        $response->assertSuccessful();
        $this->assertCount($count->count(), $response->json()['data']);
    }

    public function test_it_should_be_return_book_by_filter_title()
    {
        //arrange
        $password = \Str::random(10);
        $user = UserFactory::new()->create(['password' => $password]);
        $token = $this->post('/v1/auth/token', [
            'email' => $user->email,
            'password' => $password
        ])->json()['data']['token'];
        $title = \Str::random();
        $count = BookFactory::new()->count(10)->create(['title' => $title])->count();
        $more = BookFactory::new()->count(10)->create()->count();
        $total = $count + $more;

        //act
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->get("/v1/books?title={$title}");

        //assert
        $response->assertSuccessful();

        $this->assertCount($count, $response->json()['data']);
        $this->assertNotEquals($total, count($response->json()['data']));
    }
}
