<?php
function operacion()
{
    session_start();
    include "./db.php";

    $descripcion = $_POST['descripcion'];
    $monto = $_POST['monto'];
    $tipo_gasto = $_POST['gasto'];
    $tipo_categoria = $_POST['categoria'];
    $fecha = $_POST['fecha'];
    $id = $_POST['id'];

    if (isset($_POST['btn_guardar'])) {
        $query = "INSERT INTO tlb_operacion (descripcion,monto,id_gasto,id_categoria,fecha) VALUES (?,?,?,?,?)";
        $rs = $conn->prepare($query);

        if ($rs) {
            $rs->bind_param("siiis", $descripcion, $monto, $tipo_gasto, $tipo_categoria, $fecha);
            $rs->execute();
            if ($rs->affected_rows > 0) {
                $_SESSION['exito'] = "Se guardo correctamente";
                header("location: http://localhost/final/");
                $conn->close();
                exit();
            } else {
                $_SESSION['error'] = "Ocurrio un error al guardar";
                $conn->close();
                exit();
            }
        } else {
            header("location: http://localhost/final/");
            $_SESSION['error'] = "Error";
            $conn->close();
            exit();
        }
    } else if (isset($_POST['btn_actualizar'])) {
        $query = "UPDATE  tlb_operacion SET descripcion=?,monto=?,id_gasto=?,id_categoria=?,fecha=? WHERE id_operacion=?";
        $rs = $conn->prepare($query);

        if ($rs) {
            $rs->bind_param("siiisi", $descripcion, $monto, $tipo_gasto, $tipo_categoria, $fecha, $id);
            $rs->execute();
            if ($rs->affected_rows > 0) {
                $_SESSION['exito'] = "Se Actualizo correctamente";
                header("location: http://localhost/final/");
                $conn->close();
                exit();
            } else {
                $_SESSION['error'] = "Error al Actulizar";
                $conn->close();
                exit();
            }
        } else {
            header("location: http://localhost/final/");
            $_SESSION['error'] = "Error";
            $conn->close();
            exit();
        }
    } else if (isset($_POST['categoria_f']) || isset($_POST['gasto_f'])) {
        header("location: http://localhost/final/");
        exit();
    } else if (isset($_POST['btn_eliminar']) && !empty($_POST['btn_eliminar'])) {
        $id_eliminar = $_POST['btn_eliminar'];
        $query = "DELETE FROM tlb_operacion WHERE id_operacion= '$id_eliminar' ";
        $rs = mysqli_query($conn, $query);

        if ($rs) {
            echo "Eliminado";
            header("location: http://localhost/final/");
        } else {
            echo "No eliminado";
            header("location: http://localhost/final/");
        }
    }
}

operacion();
