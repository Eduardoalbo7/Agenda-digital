<?php
header('Content-Type: application/json');
include 'conexion_be.php';

$data = json_decode(file_get_contents('php:input'), true);

$sala_id = (int)$data['sala_id'];
$fecha = mysqli_real_escape_string($conexion, $data['fecha']);
$periodo = mysqli_real_escape_string($conexion, $data['periodo']);

// Determinar hora inicio y fin según el periodo
$horas = [
    '1A' => ['inicio' => '08:00:00', 'fin' => '08:45:00'],
    '1B' => ['inicio' => '08:45:00', 'fin' => '09:30:00'],
    '2A' => ['inicio' => '09:55:00', 'fin' => '10:40:00'],
    '2B' => ['inicio' => '10:40:00', 'fin' => '11:25:00'],
    '3A' => ['inicio' => '11:40:00', 'fin' => '12:25:00'],
    '3B' => ['inicio' => '12:25:00', 'fin' => '13:10:00'],
    '4A' => ['inicio' => '13:40:00', 'fin' => '14:25:00'],
    '4B' => ['inicio' => '14:25:00', 'fin' => '15:10:00']
];

$hora_inicio = $horas[$periodo]['inicio'];
$hora_fin = $horas[$periodo]['fin'];

$query = "SELECT COUNT(*) as total FROM reservas 
          WHERE id_sala = ? 
          AND fecha_reserva = ? 
          AND estado != 'cancelada'
          AND ((hora_inicio <= ? AND hora_fin > ?)
          OR (hora_inicio < ? AND hora_fin >= ?)
          OR (? <= hora_inicio AND ? >= hora_fin))";

$stmt = mysqli_prepare($conexion, $query);
mysqli_stmt_bind_param($stmt, "isssssss", 
    $sala_id, $fecha, $hora_inicio, $hora_inicio, 
    $hora_fin, $hora_fin, $hora_inicio, $hora_fin);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($resultado);

echo json_encode(['disponible' => $row['total'] == 0]);