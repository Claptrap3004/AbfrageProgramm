<?php
//
//namespace quiz;
//
//
//class Model extends DataBase
//{
//    public function create(array $data)
//    {
//        $items = '';
//        $values = '';
//        foreach ($data as $key => $value) {
//            $items .= $key . ', ';
//            $values .= ':' . $key . ', ';
//        }
//        $items = rtrim($items, ', ');
//        $values = rtrim($values, ', ');
//        $query = "INSERT INTO $this->tableName (" . $items . ") VALUES (" . $values . ")";
//        return $this->query($query, $data);
//
//    }
//
//    public function findAll(): array|false
//    {
//        $query = "SELECT * FROM $this->tablename";
//        return $this->query($query);
//    }
//
//    public function findById(int $id): object|bool|null
//    {
//        $query = "SELECT * FROM $this->tablename WHERE id = :id;";
//        $result = $this->query($query, [':id' => $id]);
//        return is_array($result) ? $result[0] : false;
//    }
//
//    public function update(array $data): bool|array
//    {
//        $data['id'] = (int)$data['id'];
//        $items = '';
//        foreach ($data as $key => $value) {
//            if ($key == 'id') continue;
//            $items .= $key . '=:' . $key . ', ';
//        }
//        $items = rtrim($items, ', ');
//        $query = "UPDATE $this->tablename SET " . $items . " WHERE id = :id;";
//
//        return $this->query($query, $data);
//
//    }
//
//    public function delete(int $id): bool|array
//    {
//        $query = "DELETE FROM $this->tablename WHERE id = :id";
//        return $this->query($query, [':id' => $id]);
//    }
//
//}