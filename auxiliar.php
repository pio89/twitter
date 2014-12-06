<?php

  function conectar()
  {
    return pg_connect("host=localhost user=twitter password=twitter
                       dbname=twitter");
  }
