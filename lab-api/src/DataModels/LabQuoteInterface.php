<?php
/**
 * Created by PhpStorm.
 * User: Lab916
 * Date: 2/8/2018
 * Time: 2:29 PM
 */

namespace Lab916\Cloud\Quote\DataModel;

interface LabQuoteInterface
{
    /**
     * List all the quotes in our Table.
     *
     * @param int $limit - how many quotes can be fethed at once?
     * @param null $cursor - returned by an earlier call to listQuotes()
     *
     * @return array ['quotes' => array of associative arrays mapping column
     *                  name to column value,
     *              'cursor' => pass to next call to listQuotes() to fetch
     *              more quotes]
     */
    public function listQuotes($limit = 30, $cursor = null);

    /**
     * Creates a new quote in the data model
     *
     * @param array $quote - an assoc.arr
     * @param null $id - the id
     *
     * @return mixed - the id of the new book.
     */
    public function create($quote, $id = null);

    /**
     * Reads a book from the data model
     *
     * @param int $id - the id of the quote to read
     *
     * @return mixed - an assoc.arr representing the book if found. Otherwise a false value
     */
    public function read($id);

    /**
     * Updates a quote in the data model
     *
     * @param array $quote - An associative array representing the quote
     * @param null $id - The old id of the book
     *
     * @return int - The number of books updated
     */
    // public function update($quote, $id = null);

    /**
     * Deletes a quote from the data model
     * @param int $id - The quote id
     *
     * @return int - The number of quotes deleted.
     */
    // public function delete($id);
}