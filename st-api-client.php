<?php

  class STAPIClient
  {
    private $connect = false;
    private $host = 'saransktoday.ru';
    private $port = '80';
    private $version = '1.1';
    private $action = '';
    private $offset = 0;
    private $limit = 100;
    private $order = 'asc';
    private $category = false;
    private $date = false;
    private $periodStart = false;
    private $periodEnd = false;
    private $id = false;

    private function __construct($action)
    {
      $this->action = $action;
      $this->connect = fsockopen($this->host, $this->port, $errno, $errstr, 30);
      return $this;
    }

    public static function categorieslist()
    {
      return new self('categorieslist');
    }

    public static function eventslist()
    {
      return new self('eventslist');
    }

    public static function event()
    {
      return new self('event');
    }

    public static function filmslist()
    {
      return new self('filmslist');
    }

    public function id($id)
    {
      $this->id = $id;
      return $this;
    }

    public function offset($offset)
    {
      $this->offset = $offset;
      return $this;
    }

    public function limit($limit)
    {
      $this->limit = $limit;
      return $this;
    }

    public function order($order)
    {
      $this->order = $order;
      return $this;
    }

    public function category($category)
    {
      $this->category = $category;
      return $this;
    }

    public function date($date)
    {
      $this->date = $date;
      return $this;
    }

    public function periodStart($date)
    {
      $this->periodStart = $date;
      return $this;
    }

    public function periodEnd($date)
    {
      $this->periodEnd = $date;
      return $this;
    }

    public function exec(& $error = false)
    {
      switch ($this->action)
      {
        case 'categorieslist':
          return $this->categorieslistExec($error);
          break;
        case 'eventslist':
          return $this->eventslistExec($error);
          break;
        case 'event':
          return $this->eventExec($error);
          break;
        case 'filmslist':
          return $this->filmslistExec($error);
          break;
        default:
          $error = 'Unknown type';
      }
    }

    private function categorieslistExec(& $error)
    {
      $params = array(
        'order' => $this->order,
        'offset' => $this->offset,
        'limit' => $this->limit
      );
      return $this->execWithParams($params, $error);
    }

    private function filmslistExec(& $error)
    {
      $params = array();
      return $this->execWithParams($params, $error);
    }

    private function eventslistExec(& $error)
    {
      $params = array(
        'order' => $this->order,
        'offset' => $this->offset,
        'limit' => $this->limit
      );
      if ($this->date) {
        $params['date'] = $this->date;
      }
      if ($this->category) {
        $params['category'] = $this->category;
      }
      if ($this->periodStart) {
        $params['period_start'] = $this->periodStart;
      }
      if ($this->periodEnd) {
        $params['period_end'] = $this->periodEnd;
      }
      return $this->execWithParams($params, $error);
    }

    private function eventExec(& $error)
    {
      if (!$this->id) {
        $error = 'There is no `id`';
      }
      $params = array('id' => $this->id);
      return $this->execWithParams($params, $error);
    }

    private function execWithParams($params, & $error)
    {
      if ($this->connect) {
        $values = array();
        foreach ($params as $name => $value ){
          $values[] = urlencode($name).'='.urlencode($value);
        }
        $values = implode('&', $values);
        $request  = 'GET /api/'.$this->version.'/'.$this->action.'/?'.$values.' HTTP/1.1'."\r\n";
        $request .= 'Host: '.$this->host."\r\n";
        $request .= "Connection: Close\r\n";
        $request .= "\r\n";

        fwrite($this->connect, $request);
        $content = '';
        while (!feof($this->connect)) {
          $content .= fgets($this->connect, 128);
        }
        if (strpos($content, "\r\n\r\n") !== false) {
          $content = substr($content, strpos($content, "\r\n\r\n"));
          $content = ltrim($content);
        }
        elseif (strpos($content, "\n\n") !== false) {
          $content = substr($content, strpos($content, "\n\n"));
          $content = ltrim($content);
        }
        $content = json_decode($content, true);
        if (!$content['error']) {
          return $content['data'];
        }
        $error = $content['error'];
        return array();
      }
    }
  }
