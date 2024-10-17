<?php
         $sql = "INSERT INTO report (temp, wind, rain, date) VALUES (?, ?, ?, ?)";
          $duplicates = "SELECT * FROM report WHERE date = (?)";
          $averagetemppast = "SELECT * FROM report ORDER BY date DESC LIMIT 5 OFFSET 5";
           $averagetempfut = "SELECT * FROM report LIMIT 5 OFFSET 5";