<?php

declare(strict_types=1);

/**
 * @package   Ultimate Menu mod
 * @version   3.0.0
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2014, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */

namespace CustomForm;

use SMF\Db\DatabaseApi as Db;

/**
 * Provides a set of utility methods for working with the database.
 *
 * These methods can be used to simplify common database operations, such as
 * fetching data, inserting new rows, updating existing rows, and deleting rows.
 */
class DatabaseHelper
{
	/**
	 * Fetches data from the database based on specified criteria.
	 *
	 * @param array $selects Table columns to select.
	 * @param string $from FROM clause.
	 * @param array $params Parameters to substitute into query text.
	 * @param array $joins Zero or more *complete* JOIN clauses.
	 *    E.g.: 'LEFT JOIN messages AS m ON (a.id_msg = m.id_msg)'
	 * @param array $where Zero or more conditions for the WHERE clause.
	 *    Conditions will be placed in parentheses and concatenated with AND.
	 *    If this is left empty, no WHERE clause will be used.
	 * @param array $order Zero or more conditions for the ORDER BY clause.
	 *    If this is left empty, no ORDER BY clause will be used.
	 * @param array $group Zero or more conditions for the GROUP BY clause.
	 *    If this is left empty, no GROUP BY clause will be used.
	 * @param int|null $limit Maximum number of results to retrieve.
	 *    If left empty, no LIMIT clause is used, returning all results.
	 * @param int|null $offset Offset for results.
	 *    If this is left empty, no OFFSET clause will be used.
	 *
	 * @return \Generator<array> Iterating over the result gives database rows as associative arrays.
	 */
	public static function fetchBy(
		array $selects,
		string $from,
		array $params = [],
		array $joins = [],
		array $where = [],
		array $order = [],
		array $group = [],
		?int $limit = null,
		?int $offset = null,
	): \Generator {
		$request = Db::$db->query(
			'',
			'SELECT ' . implode(', ', $selects) . '
			FROM ' . implode("\n\t\t\t\t", array_merge([$from], $joins)) . ($where === [] ? '' : '
			WHERE (' . implode(') AND (', $where) . ')') . ($group === [] ? '' : '
			GROUP BY ' . implode(', ', $group)) . ($order === [] ? '' : '
			ORDER BY ' . implode(', ', $order)) . ($limit !== null ? '
			LIMIT ' . $limit : '') . ($offset !== null ? '
			OFFSET ' . $offset : ''),
			$params,
		);

		while ($row = Db::$db->fetch_assoc($request)) {
			yield $row;
		}
		Db::$db->free_result($request);
	}

	/**
	 * Inserts data into a table.
	 *
	 * @param string $table_name Name of the table to insert data into.
	 * @param array $columns Associative array of column name => [type, data].
	 */
	public static function insert(string $table_name, array $columns): void
	{
		$column_params = [];
		$where_params = [[]];

		foreach ($columns as $column => [$type, $data]) {
			$column_params[$column] = $type;
			$where_params[0][] = $data;
		}

		Db::$db->insert('insert', $table_name, $column_params, $where_params, []);
	}

	/**
	 * Updates data in a table.
	 *
	 * @param string $table_name Name of the table to update.
	 * @param array $columns Associative array of column name => [type, data].
	 * @param string $col Column to update.
	 * @param int $id ID of the row to update.
	 */
	public static function update(string $table_name, array $columns, string $col, int $id): void
	{
		$sql = '';
		$where_params = ['id' => $id, 'col' => $col];

		foreach ($columns as $column => [$type, $data]) {
			// Are we restricting the length?
			if (str_contains($type, 'string-')) {
				$sql .= $column . ' = ' . sprintf('SUBSTRING({string:%1$s}, 1, ' . substr($type, 7) . ') ', $column);
			} else {
				$sql .= $column . ' = {' . $type . ':' . $column . '} ';
			}
			$where_params[$column] = $data;
		}

		Db::$db->query(
			'',
			'
			UPDATE ' . $table_name . '
			SET ' . $sql . '
			WHERE {identifier:col} = {int:id}',
			$where_params,
		);
	}

	/**
	 * Deletes a record from the specified table based on the given column and ID.
	 *
	 * @param string $table_name Name of the table from which to delete the record.
	 * @param string $col Column to match for deletion.
	 * @param int $id ID of the record to delete.
	 */
	public static function delete(string $table_name, string $col, int $id): void
	{
		Db::$db->query(
			'',
			'
			DELETE FROM ' . $table_name . '
			WHERE {identifier:col} = {int:id}',
			[
				'id' => $id,
				'col' => $col,
			],
		);
	}

	/**
	 * Deletes multiple records from the specified table based on the given column and array of IDs.
	 *
	 * @param string $table_name Name of the table from which to delete the records.
	 * @param string $col Column to match for deletion.
	 * @param array $ids Array of IDs of the records to delete.
	 */
	public static function deleteMany(string $table_name, string $col, array $ids): void
	{
		Db::$db->query(
			'',
			'
			DELETE FROM ' . $table_name . '
			WHERE {identifier:col} IN ({array_int:ids})',
			[
				'ids' => $ids,
				'col' => $col,
			],
		);
	}

	/**
	 * Deletes all records from the specified table.
	 *
	 * @param string $table_name Name of the table from which to delete all records.
	 */
	public static function deleteAll(string $table_name): void
	{
		Db::$db->query('', 'TRUNCATE ' . $table_name);
	}

	/**
	 * Increments the value of a column in the specified table based on the given condition.
	 *
	 * @param string $table_name Name of the table in which to increment the column value.
	 * @param string $increment_col Column to increment.
	 * @param string $where_col Column to match for the condition.
	 * @param int $id ID of the record to match for the condition.
	 */
	public static function increment(string $table_name, string $increment_col, string $where_col, int $id): void
	{
		Db::$db->query(
			'',
			'
			UPDATE ' . $table_name . '
			SET {identifier:col} = {identifier:col} + 1
			WHERE {identifier:where_col} = {int:id}',
			[
				'id' => $id,
				'where_col' => $where_col,
				'col' => $increment_col,
			],
		);
	}
}
