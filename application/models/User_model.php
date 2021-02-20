<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    protected $table = "user";
    protected $idColumn = "id";
    protected $defaultOrderColumn = "created_at";
    protected $defaultOrderSort = "ASC";
    protected $defaultFetchColumns = [
        "id",
        "created_at",
        "updated_at",
        "name",
        "icon_url",
        "about",
    ];
    protected $ignoreDeletedUserQuery = "deleted_at IS NOT NULL";

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * DBをfetchする時のLimitをセットする
     *
     * @param int|bool $limit
     * @return void
     */
    public function setLimit(
        $limit
    ){
        if( !is_bool($limit) ){
            $this->db->limit($limit);
        }
    }

    /**
     * 削除されていないユーザ情報を取得する
     *
     * @param int|bool $limit
     * @return array
     */
    public function fetchUsers(
        $limit
    ) :array {
        // limitが数字の時はfetch制限をする
        $this->setLimit($limit);

        $fetchColumns = implode(", ", $this->defaultFetchColumns);
        return $this->db
        ->select( $fetchColumns )
        ->where( $this->ignoreDeletedUserQuery )
        ->order_by(
            $this->defaultOrderColumn,
            $this->defaultOrderSort
        )
        ->get( $this->table )
        ->result_array();
    }

    /**
     * 削除済みユーザを含む全ての情報を取得する
     *
     * @param int|bool $limit
     * @return array
     */
    public function fetchAllUsers(
        $limit
    ) :array {
        $this->setLimit($limit);
        return $this->db
        ->order_by(
            $this->defaultOrderColumn,
            $this->defaultOrderSort
        )
        ->get( $this->table )
        ->result_array();
    }

    /**
     * ユーザを作成(Insert)する
     *
     * @param array $user
     * @return void
     */
    public function insert(
        array $user
    ){
        $this->db
        ->insert($this->table, $user);
    }

    /**
     * ユーザ情報を更新(Update)する
     *
     * @param int|string $id
     * @param array $user
     * @return void
     */
    public function update(
        $id,
        array $user
    ){
        $this->db->where($this->idColumn, $id)
        ->update($this->table, $user);
    }

    /**
     * ユーザを削除(Delete)する
     *
     * @param int|string $id
     * @return void
     */
    public function delete(
        $id
    ){
        $this->db->where($this->idColumn, $id)
        ->delete( $this->table );
    }
}