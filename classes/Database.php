<?php

class Database {
  private $host;
  private $db;
  private $user;
  private $pwd;
  private $opts = array( "=", ">", "<", ">=", "<=" );

  if ( $_SERVER[ 'SERVER_ADDR' ] !== $_SERVER[ 'REMOTE_ADDR' ] ) {
    $host = "localhost";
    $user = "admin";
    $pwd = "N0rthP013$";
  } else {
    $host = "localhost";
    $user = "root";
    $pwd = "root";
  }

  private function __construct() {
    try {
      $this->pdo = new PDO( "mysql:host=$host;dbname=$db;", $user, $pwd );
    } catch ( PDOException $e ) {
      die( $e->getMessage() );
    }
  }

  public static function getInstance() {
    if ( !isset( self::$_instance ) ) {
      self::$_instance = new Database();
    }
    return self::$_instance;
  }

  public function query( $sql, $params = array() ) {
    $this->error = false;
    if ( $this->query = $this->pdo->prepare( $sql ) ) {
      if ( count( $params ) ) {
        foreach ( $params as $param ) {
          $this->query->bindValue( $i, $param );
          $i++;
        }
      }
      if ( $this->query->execute() ) {
        $this->results = $this->query->fetchAll( PDO::FETCH_OBJ );
        $this->rowCount = $this->query->rowCount();
      } else {
        $this->error = true;
      }
    }
    return $this;
  }
  public function error() {
    return $this->error;
  }
  public function results() {
    return $this->results;
  }
  public function count() {
    $this->rowCount();
  }

  private function action( $action, $table, $where = array(), $orderby = array(), $limit ) {
    $orders = array( "asc", "desc" );
    if ( $action === "select" || $action === "delete" && $table ) {
      if ( $action == "select" )$action = "select *";
      $param_count = count( $where );
      $order_count = count( $orderby );
      if ( $param_count === 0 && $action === "select *" || $param_count === 3 ) {
        $sql = "{$action} from {$table}";
        if ( $param_count === 3 ) {
          $col = $where[ 0 ];
          $opt = $where[ 1 ];
          $val = $where[ 2 ];
          if ( in_array( $opt, $this->opts ) !== false ) {
            $sql .= "where {$col} {$opt} ?";
            $params = array( $val );
          }
        }
        if ( $order_count === 2 ) {
          $col = $orderby[ 0 ];
          $by = $orderby[ 1 ];
          if ( $col && in_array( $by, $orders ) !== false )$sql .= " order by {$col} {$by}";
        }
        if ( $limit && is_numeric( $limit ) !== false )$sql .= " limit " . number_format( $limit );
        if ( $sql ) {
          if ( !$this->query( $sql, $params )->error() ) return $this;
        }
      }
    }
    return false;
  }
  public function modify( $action, $table, $fields = array(), $where = array() ) {
    if ( count( $fields ) ) {
      $cols = array_keys( $fields );
      if ( $action === "insert" ) {
        $values = array();
        $i = 0;
        foreach ( $fields as $field ) {
          $values .= "?";
          if ( $field !== end( $fields ) )$values .= ",";
          ++$i;
        }
      } else if ( $action === "update" ) {
        $sets = array();
        foreach ( $fields as $field ) {
          array_push( $sets, "$field = ?" );
        }
        if ( count( $where ) === 3 ) {
          $col = $where[ 0 ];
          $opt = $where[ 1 ];
          $val = $where[ 2 ];
          if ( in_array( $opt, $this->opts ) !== false ) {
            $wheres = "where {$col} {$opt} ?";
          }
        }
      }
      if ( $action === "insert" )$sql = "insert into {$table} (" . implode( '`,`', $cols ) . ") values ({$values})";
      else if ( $action === "update" )$sql = "update {$table} set " . implode( ', ', $sets ) . " {$wheres}";
      if ( !$this->query( $sql, $fields )->error() ) {
        return true;
      }
    }
    return false;
  }
  public function insert( $table, $fields ) {
    return $this->modify( 'insert', $table, $fields );
  }
  public function get( $table, $where ) {
    return $this->action( 'select', $table, $where );
  }
  public function update( $table, $fields, $where ) {
    return $this->modify( 'update', $table, $fields, $where );
  }
  public function delete( $table, $where ) {
    return $this->action( 'delete', $table, $where );
  }
}