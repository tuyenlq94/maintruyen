<?php

namespace T_Shop\Repositories;

abstract class BaseRepository
{
    protected $table;

    protected $primaryKey = 'id';

    public function table()
    {
        global $wpdb;

        return $wpdb->prefix . $this->table;
    }

    public function find($id)
    {
        global $wpdb;

        return $wpdb->get_row(
            $wpdb->prepare("
                SELECT *
                FROM {$this->table()}
                WHERE {$this->primaryKey} = %d
                LIMIT 1
            ", $id)
        );
    }

    public function all($limit = 20)
    {
        global $wpdb;

        return $wpdb->get_results(
            $wpdb->prepare("
                SELECT *
                FROM {$this->table()}
                ORDER BY {$this->primaryKey} DESC
                LIMIT %d
            ", $limit)
        );
    }

    public function create(array $data)
    {
        global $wpdb;

        $wpdb->insert(
            $this->table(),
            $data
        );

        return $wpdb->insert_id;
    }

    public function update($id, array $data)
    {
        global $wpdb;

        return $wpdb->update(
            $this->table(),
            $data,
            [
                $this->primaryKey => $id
            ]
        );
    }

    public function delete($id)
    {
        global $wpdb;

        return $wpdb->delete(
            $this->table(),
            [
                $this->primaryKey => $id
            ]
        );
    }
}