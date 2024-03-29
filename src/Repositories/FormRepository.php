<?php


namespace Quack\Form\Repositories;

use Quack\Form\Database\Database;

class FormRepository
{
    protected const TABLE_NAME = 'form_entries';
    private $db;
    private $wpdb;
    private $full_table_name;

    public function __construct()
    {
        $db = Database::instance();
        $this->db = $db;
        $this->wpdb = $db->wpdb;
        $this->full_table_name = $db->full_table_prefix . static::TABLE_NAME;
    }

    public function get_one(int $id)
    {
        $row = $this->wpdb->get_row(
            $this->wpdb->prepare(
                "SELECT * from {$this->full_table_name} WHERE `id` = %d",
                [$id]
            )
        );

        return $row;
    }

    public function get_all()
    {
        $results = $this->wpdb->get_results(
            "SELECT * from {$this->full_table_name}",
            ARRAY_A
        );

        return $results;
    }

    public function insert($data)
    {
        $inserted = $this->wpdb->insert(
            $this->full_table_name,
            [
                'email' => $data['email'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'date_of_birth' => $data['date_of_birth'],
                'phone_number' => $data['phone_number']
            ],
            ['%s', '%s', '%s', '%s', '%s']
        );

        if ($inserted) {
            return $this->wpdb->insert_id;
        } else {
            return false;
        }
    }


    public function delete(int $id)
    {
        return $this->wpdb->delete(
            $this->full_table_name,
            ['id' => $id],
            ['%d']
        );
    }
}
