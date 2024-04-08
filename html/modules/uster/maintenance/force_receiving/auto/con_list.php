<?php

$no_req  = $_GET["search"];
$db   = getDB("storage");

if ($no_req != '') {
  $query_list    = "SELECT
        NO_CONTAINER,
        LOCATION,
        CASE
            WHEN (
                SELECT COUNT(*)
                FROM CONTAINER_RECEIVING
                WHERE NO_CONTAINER = MASTER_CONTAINER.NO_CONTAINER
                AND AKTIF = 'Y') = 0 THEN 'NON-AKTIF'
            WHEN (
                SELECT COUNT(*)
                FROM CONTAINER_RECEIVING
                WHERE NO_CONTAINER = MASTER_CONTAINER.NO_CONTAINER
                AND AKTIF = 'Y') >= 1 THEN 'AKTIF'
        END AS RECEIVING,
        CASE
            WHEN (
                SELECT COUNT(*)
                FROM CONTAINER_DELIVERY
                WHERE NO_CONTAINER = MASTER_CONTAINER.NO_CONTAINER
                AND AKTIF = 'Y') = 0 THEN 'NON-AKTIF'
            WHEN (
                SELECT COUNT(*)
                FROM CONTAINER_DELIVERY
                WHERE NO_CONTAINER = MASTER_CONTAINER.NO_CONTAINER
                AND AKTIF = 'Y') >= 1 THEN 'AKTIF'
        END AS DELIVERY,
        CASE
            WHEN (
                SELECT COUNT(*)
                FROM CONTAINER_STRIPPING
                WHERE NO_CONTAINER = MASTER_CONTAINER.NO_CONTAINER
                AND AKTIF = 'Y') = 0 THEN 'NON-AKTIF'
            WHEN (
                SELECT COUNT(*)
                FROM CONTAINER_STRIPPING
                WHERE NO_CONTAINER = MASTER_CONTAINER.NO_CONTAINER
                AND AKTIF = 'Y') >= 1 THEN 'AKTIF'
        END AS STRIPPING,
        CASE
            WHEN (
                SELECT COUNT(*)
                FROM CONTAINER_STUFFING
                WHERE NO_CONTAINER = MASTER_CONTAINER.NO_CONTAINER
                AND AKTIF = 'Y') = 0 THEN 'NON-AKTIF'
            WHEN (
                SELECT COUNT(*)
                FROM CONTAINER_STUFFING
                WHERE NO_CONTAINER = MASTER_CONTAINER.NO_CONTAINER
                AND AKTIF = 'Y') >= 1 THEN 'AKTIF'
        END AS STUFFING
      FROM
        MASTER_CONTAINER
      WHERE
        NO_CONTAINER = '$no_req'";
} else {
  $query_list    = "SELECT
      NO_CONTAINER,
      LOCATION,
      CASE
          WHEN (
              SELECT COUNT(*)
              FROM CONTAINER_RECEIVING
              WHERE NO_CONTAINER = MASTER_CONTAINER.NO_CONTAINER
              AND AKTIF = 'Y') = 0 THEN 'NON-AKTIF'
          WHEN (
              SELECT COUNT(*)
              FROM CONTAINER_RECEIVING
              WHERE NO_CONTAINER = MASTER_CONTAINER.NO_CONTAINER
              AND AKTIF = 'Y') >= 1 THEN 'AKTIF'
      END AS RECEIVING,
      CASE
          WHEN (
              SELECT COUNT(*)
              FROM CONTAINER_DELIVERY
              WHERE NO_CONTAINER = MASTER_CONTAINER.NO_CONTAINER
              AND AKTIF = 'Y') = 0 THEN 'NON-AKTIF'
          WHEN (
              SELECT COUNT(*)
              FROM CONTAINER_DELIVERY
              WHERE NO_CONTAINER = MASTER_CONTAINER.NO_CONTAINER
              AND AKTIF = 'Y') >= 1 THEN 'AKTIF'
      END AS DELIVERY,
      CASE
          WHEN (
              SELECT COUNT(*)
              FROM CONTAINER_STRIPPING
              WHERE NO_CONTAINER = MASTER_CONTAINER.NO_CONTAINER
              AND AKTIF = 'Y') = 0 THEN 'NON-AKTIF'
          WHEN (
              SELECT COUNT(*)
              FROM CONTAINER_STRIPPING
              WHERE NO_CONTAINER = MASTER_CONTAINER.NO_CONTAINER
              AND AKTIF = 'Y') >= 1 THEN 'AKTIF'
      END AS STRIPPING,
      CASE
          WHEN (
              SELECT COUNT(*)
              FROM CONTAINER_STUFFING
              WHERE NO_CONTAINER = MASTER_CONTAINER.NO_CONTAINER
              AND AKTIF = 'Y') = 0 THEN 'NON-AKTIF'
          WHEN (
              SELECT COUNT(*)
              FROM CONTAINER_STUFFING
              WHERE NO_CONTAINER = MASTER_CONTAINER.NO_CONTAINER
              AND AKTIF = 'Y') >= 1 THEN 'AKTIF'
      END AS STUFFING
    FROM
      MASTER_CONTAINER
    WHERE
      NO_CONTAINER = ''";
    }


$result      = $db->query($query_list);
$row      = $result->getAll();

echo json_encode($row);
