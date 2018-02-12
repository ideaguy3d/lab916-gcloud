<?php

/*
 * Copyright 2015 Google Inc. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

//namespace Google\Cloud\Samples\Bookshelf;

/*
 * Adds all the controllers to $app.  Follows Silex Skeleton pattern.
 */

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Google\Cloud\Samples\Bookshelf\DataModel\DataModelInterface;
use Lab916\Cloud\Quote\DataModel\DataModelInterfaceLab916;

/*
 $app->get('/', function (Request $request) use ($app) {
    // return $app->redirect('/home/');
    return "hi";
});
*/

// test route
$app->get('/quote/test/one/', function (Request $request) use ($app) {
    $data = [];

    $data["email"] = $request->get("email");
    $data["name"] = $request->get("name");
    $data["status"] = "It did succeed, hopefully with good results :)";

    $pr = print_r($data);

    return $pr;
});

$app->get('/lab/quotes/', function (Request $request) use ($app) {
    /** @var DataModelInterfaceLab916 $model */
    $model = $app['quote.model'];
    /** @var Twig_Environment $twig */
    $twig = $app['twig'];

    $token = $request->query->get('email');
    $quoteList = $model->listQuotes(30, null);

    return $quoteList['quotes'];
});

$app->get('/lab/quotes/add', function (Request $request) use ($app) {
    /** @var DataModelInterfaceLab916 $model */
    $model = $app['quote.model'];
    $quote = $request->get('email');
    $pr = print_r($quote);
    // $id = $model->create($quote);

    // return $app->redirect("/lab/quotes"); // "/$id"
    return "<br><br>quote request =<br>" . $quote . "<br><br>";
});

$app->get('/l9/quotes/', function (Request $req) use ($app) {
    $cursel = $req->query->get('currently-selling');
    $model = $app['bookshelf.model'];
    $labUid = rand(1, 1000);
    $cell = array("currently_selling" => $cursel);

    $model->create($cell, $labUid);
    return new Response($cell);
});

$app->get('/home/', function (Request $request) use ($app) {
    return "<h1>Lab916 API back end</h1>";
});

// [START index]
$app->get('/books/', function (Request $request) use ($app) {
    /** @var DataModelInterface $model */
    $model = $app['bookshelf.model'];
    /** @var Twig_Environment $twig */
    $twig = $app['twig'];
    $token = $request->query->get('page_token');
    $bookList = $model->listBooks($app['bookshelf.page_size'], $token);

    return $twig->render('list.html.twig', array(
        'books' => $bookList['books'],
        'next_page_token' => $bookList['cursor'],
    ));
});
// [END index]

// [START add]
$app->get('/books/add', function () use ($app) {
    /** @var Twig_Environment $twig */
    $twig = $app['twig'];

    return $twig->render('form.html.twig', array(
        'action' => 'Add',
        'book' => array(),
    ));
});

$app->post('/books/add', function (Request $request) use ($app) {
    /** @var DataModelInterface $model */
    $model = $app['bookshelf.model'];
    $book = $request->request->all();
    echo "<br>The incoming post data<br>";
    $pr = print_r($book);
    $id = $model->create($book);

    // return $app->redirect("/books/$id");
    return $pr;
});
// [END add]

// [START show]
$app->get('/books/{id}', function ($id) use ($app) {
    /** @var DataModelInterface $model */
    $model = $app['bookshelf.model'];
    $book = $model->read($id);
    if (!$book) {
        return new Response('', Response::HTTP_NOT_FOUND);
    }
    /** @var Twig_Environment $twig */
    $twig = $app['twig'];

    return $twig->render('view.html.twig', array('book' => $book));
});
// [END show]

// [START edit]
$app->get('/books/{id}/edit', function ($id) use ($app) {
    /** @var DataModelInterface $model */
    $model = $app['bookshelf.model'];
    $book = $model->read($id);
    if (!$book) {
        return new Response('', Response::HTTP_NOT_FOUND);
    }
    /** @var Twig_Environment $twig */
    $twig = $app['twig'];

    return $twig->render('form.html.twig', array(
        'action' => 'Edit',
        'book' => $book,
    ));
});

$app->post('/books/{id}/edit', function (Request $request, $id) use ($app) {
    $book = $request->request->all();
    $book['id'] = $id;
    /** @var DataModelInterface $model */
    $model = $app['bookshelf.model'];
    if (!$model->read($id)) {
        return new Response('', Response::HTTP_NOT_FOUND);
    }
    if ($model->update($book)) {
        return $app->redirect("/books/$id");
    }

    return new Response('Could not update book');
});
// [END edit]

// [START delete]
$app->post('/books/{id}/delete', function ($id) use ($app) {
    /** @var DataModelInterface $model */
    $model = $app['bookshelf.model'];
    $book = $model->read($id);
    if ($book) {
        $model->delete($id);
        return $app->redirect('/books/', Response::HTTP_SEE_OTHER);
    }

    return new Response('', Response::HTTP_NOT_FOUND);
});
// [END delete]

