<?php
include "./db.php"
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="./bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.5/dist/sweetalert2.min.css">
    <!-- SweetAlert JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.5/dist/sweetalert2.min.js"></script>

</head>

<body>

    <!-- mensaje del evio del correo -->
    <?php
    session_start();
    if (isset($_SESSION['exito'])) {
        $mensaje = $_SESSION['exito'];
    ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Exito',
                text: '<?= $mensaje ?>',
                confirmButtonText: "OK",
                backdrop: false,
                timerProgressBar: true,
                toast: false,
                background: ' #f3f28b',
                timer: 5000,
                position: 'center',
                allowOutsideClick: true,
                allowEnterKey: true,
                allowEscapeKey: true,
                stopKeydownPropagation: false,
                customClass: {
                    confirmButton: 'centered-btn',
                    icon: 'custom-icon-size',
                    popup: 'background',
                    title: 'custom-title-size',
                    content: 'text',
                    background: 'background'
                },
                width: '400px',
            })
        </script>
    <?php
        unset($_SESSION['exito']);
    }

    ?>
    <!-- Mensaje de error del envio del correo -->
    <?php
    if (isset($_SESSION['error'])) {
        $mensaje = $_SESSION['error'];
    ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '<?= $mensaje ?>',
                confirmButtonText: 'OK',
                backdrop: false,
                // timer: 5000,
                timerProgressBar: true,
                toast: true,
                position: 'bottom-end',
                allowOutsideClick: true,
                allowEnterKey: true,
                allowEscapeKey: true,
                stopKeydownPropagation: false,
                customClass: {
                    confirmButton: 'centered-btn',
                    icon: 'custom-icon-size',
                    popup: 'background',
                    title: 'custom-title-size',
                    content: 'text',
                    background: 'custom-background'
                }
            });
        </script>

    <?php
        unset($_SESSION['error']);
    }
    ?>
    <?php
    $query = "SELECT tlb_operacion.*,tlb_gasto.*,tlb_categoria.* FROM tlb_operacion INNER JOIN tlb_gasto on tlb_operacion.id_gasto = tlb_gasto.id_gasto INNER JOIN tlb_categoria on tlb_operacion.id_categoria=tlb_categoria.id_categoria ORDER BY tlb_operacion.id_operacion ASC";
    $query_rs = mysqli_query($conn, $query);
    $ganancias = 0;
    $gastos = 0;
    // Obtener el número del mes actual
    $mes_actual = date('n');

    // Mapeo de los nombres de los meses en inglés a español
    $meses_en_espanol = array(
        1 => 'enero',
        2 => 'febrero',
        3 => 'marzo',
        4 => 'abril',
        5 => 'mayo',
        6 => 'junio',
        7 => 'julio',
        8 => 'agosto',
        9 => 'septiembre',
        10 => 'octubre',
        11 => 'noviembre',
        12 => 'diciembre'
    );

    // Obtener el nombre del mes actual en español
    // $mes_actual_espanol = $meses_en_espanol[$mes_actual];

    if ($query_rs->num_rows > 0) {
        foreach ($query_rs as $filas) {
            // Fecha específica
            $fecha = $filas['fecha'];
            // Obtener el mes de la fecha
            $mes = date('n', strtotime($fecha));

            if ($filas['id_gasto'] == 1 && $mes == $mes_actual) {
                $gastos = $gastos + $filas['monto'];
            } else if ($filas['id_gasto'] == 2 && $mes == $mes_actual) {
                $ganancias = $ganancias + $filas['monto'];
            }
        }
    }
    $mes_actual_espanol = $meses_en_espanol[$mes_actual];
    ?>
    <div class="container-fluid cotenedor">
        <div class="container contenedor_balances">
            <div class="balance">
                <div class="header-balance">
                    <h4>Balance</h4>
                </div>
                <div class="contenedor-info">
                    <div class="informacion">
                        <p>Ganancias</p>
                        <p class="text-success">$ +<?= $ganancias ?></p>
                    </div>
                    <div class="informacion">
                        <p>Gastos</p>
                        <p class="text-danger">$ -<?= $gastos ?></p>
                    </div>
                    <div class="informacion">
                        <p style="font-weight: bolder;">Total</p>
                        <?php
                        $total = $ganancias - $gastos;
                        if ($total > 0) {
                        ?>
                            <p class="text-success" style="font-weight: bolder;">$ +<?= $total ?></p>
                        <?php
                        } else {
                        ?>
                            <p class="text-danger">$ <?= $total ?></p>
                        <?php
                        }
                        ?>
                    </div>
                </div>

            </div>
            <div class="filtro">
                <div class="cabezera-filtro">
                    <p>Filtros</p>
                    <button type="button" onclick="ocultarFiltro()" style="font-size: 13px;">Ocultar filtros</button>
                </div>
                <div class="form">
                    <form action="./validacion.php" method="post" id="myForm">
                        <div class="form-group mb-2 select">
                            <label for="" class="label-form">Tipo</label>
                            <select class="form-select form-select" aria-label=".form-select-sm example" name="gasto" id="gasto">
                                <option selected>Eliga una opción</option>
                                <?php
                                $query_c = "SELECT * FROM tlb_gasto WHERE estado=1";
                                $query_c_rs = mysqli_query($conn, $query_c);

                                if ($query_c_rs->num_rows > 0) {
                                    foreach ($query_c_rs as $filas) {
                                ?>
                                        <option value="<?= $filas['id_gasto'] ?>"><?= $filas['nombre_gasto'] ?></option>
                                <?php

                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group mb-2 select">
                            <label for="" class="label-form">Categoria</label>
                            <select class="form-select form-select" aria-label=".form-select-sm example" name="categoria_f" id="categoria">
                                <option selected>Eliga una opción</option>
                                <?php
                                $query_c = "SELECT * FROM tlb_categoria WHERE estado=1";
                                $query_c_rs = mysqli_query($conn, $query_c);

                                if ($query_c_rs->num_rows > 0) {
                                    foreach ($query_c_rs as $filas) {
                                ?>
                                        <option value="<?= $filas['id_categoria'] ?>"><?= $filas['nombre_categoria'] ?></option>
                                <?php

                                    }
                                }
                                ?>
                            </select>
                            <style>
                                .select select:focus,
                                .input input:focus {
                                    box-shadow: none;
                                    outline: none;
                                    border-color: black;
                                }
                            </style>
                        </div>
                        <div class="form-group mb-2">
                            <label for="" class="label-form">Fecha</label>
                            <input type="date" class="form-control" id="fecha" name="fecha">
                        </div>
                        <div class="form-group mb-2 select">
                            <label for="" class="label-form">Ordenado por</label>
                            <select class="form-select form-select" aria-label=".form-select-sm example" name="gasto" id="gasto">
                                <option selected>Eliga una opción</option>
                                <option value="1">DEC</option>
                                <option value="">ASC</option>
                            </select>
                        </div>
                        
                        <button type="reset" style="font-size: 13px;">Limpiar filtro</button>
                    </form>


                </div>
            </div>
        </div>
        <div class="container contenedor_tabla">
            <div class="tables" style="margin-top: 30px;">
                <div class="operaciones">
                    <p>Operaciones</p>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fa-solid fa-plus"></i> Nueva Operacion</button>
                </div>
                <div class="tabla">
                    <div class="div-scroll">
                        <table class="table t">
                            <thead class="thead">
                                <tr>
                                    <th scope="col" class="align-middle text-center">Descripción</th>
                                    <th scope="col" class="align-middle text-center">Categoria</th>
                                    <th scope="col" class="align-middle text-center">Fecha</th>
                                    <th scope="col" class="align-middle text-center">Monto</th>
                                    <th class="align-middle text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT tlb_operacion.*,tlb_gasto.*,tlb_categoria.* FROM tlb_operacion INNER JOIN tlb_gasto on tlb_operacion.id_gasto = tlb_gasto.id_gasto INNER JOIN tlb_categoria on tlb_operacion.id_categoria=tlb_categoria.id_categoria ORDER BY tlb_operacion.id_operacion ASC";
                                $query_rs = mysqli_query($conn, $query);

                                if ($query_rs->num_rows > 0) {
                                    foreach ($query_rs as $filas) {
                                ?>
                                        <tr>
                                            <td class="align-middle text-center"><?= $filas['descripcion'] ?></td>
                                            <td class="align-middle text-center"><?= $filas['nombre_categoria'] ?></td>
                                            <td class="align-middle text-center"><?= $filas['fecha'] ?></td>
                                            <th class="align-middle text-center <?= $filas['id_gasto'] == 1 ? "text-danger"  : "text-success" ?>">
                                                <?= $filas['id_gasto'] == 1 ? "-"  : "+" ?><?= $filas['monto'] ?></th>
                                            <td class="align-middle text-center">
                                                <div class="d-flex justify-content-center gap-1">
                                                    <a href="<?= $_SERVER['PHP_SELF'] ?>?id=<?= $filas['id_operacion'] ?>" class="btn btn-warning" style="font-size: 13px;" data-toggle="modal" data-target="#editarModal">Editar</a>
                                                    <form action="./validacion.php" method="post" id="eliminar-form-<?= $filas['id_operacion'] ?>">
                                                        <input type="hidden" name="btn_eliminar" value="<?= $filas['id_operacion'] ?>">
                                                        <button type="button" class="btn btn-danger" style="font-size: 13px;" onclick="confirmarEliminar(<?= $filas['id_operacion'] ?>)">Eliminar</button>
                                                    </form>
                                                    <script>
                                                        function confirmarEliminar(formId) {
                                                            Swal.fire({
                                                                icon: 'warning',
                                                                title: '¿Estas seguro que quieres eliminar este registro?',
                                                                showCancelButton: true,
                                                                confirmButtonText: "Eliminar",
                                                                cancelButtonText: 'Cancelar',
                                                                reverseButtons: true
                                                            }).then((result) => {
                                                                if (result.isConfirmed) {
                                                                    Swal.fire({
                                                                        icon: 'success',
                                                                        title: 'Registro ',
                                                                        showConfirmButton: false,
                                                                        timer: 1500
                                                                    }).then(() => {
                                                                        document.getElementById("eliminar-form-" + formId).submit();
                                                                    });
                                                                }
                                                            });
                                                        }
                                                    </script>
                                                </div>
                                            </td>
                                        </tr>

                                <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- modal -->


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar Operacion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="./validacion.php" method="post">
                        <div class="form-group mb-2 input">
                            <label for="" class="label-form">Descripción</label>
                            <input type="text" class="form-control" name="descripcion">
                        </div>
                        <div class="form-group mb-2 input">
                            <label for="" class="label-form">Monto</label>
                            <input type="number" class="form-control" name="monto">
                        </div>
                        <div class="form-group mb-2 select">
                            <label for="" class="label-form">Tipo</label>
                            <select class="form-select form-select" aria-label=".form-select-sm example" name="gasto">
                                <option selected>Eliga una opción</option>
                                <?php
                                $query_c = "SELECT * FROM tlb_gasto WHERE estado=1";
                                $query_c_rs = mysqli_query($conn, $query_c);

                                if ($query_c_rs->num_rows > 0) {
                                    foreach ($query_c_rs as $filas) {
                                ?>
                                        <option value="<?= $filas['id_gasto'] ?>"><?= $filas['nombre_gasto'] ?></option>
                                <?php

                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group mb-2 select">
                            <label for="" class="label-form">Categoria</label>
                            <select class="form-select form-select" aria-label=".form-select-sm example" name="categoria">
                                <option selected>Eliga una opción</option>
                                <?php
                                $query_c = "SELECT * FROM tlb_categoria WHERE estado=1";
                                $query_c_rs = mysqli_query($conn, $query_c);

                                if ($query_c_rs->num_rows > 0) {
                                    foreach ($query_c_rs as $filas) {
                                ?>
                                        <option value="<?= $filas['id_categoria'] ?>"><?= $filas['nombre_categoria'] ?></option>
                                <?php

                                    }
                                }
                                ?>
                            </select>
                            <style>
                                .select select:focus,
                                .input input:focus {
                                    box-shadow: none;
                                    outline: none;
                                    border-color: black;
                                }
                            </style>
                        </div>
                        <div class="form-group mb-2 input">
                            <label for="" class="label-form">Fecha</label>
                            <input type="date" class="form-control" name="fecha">
                        </div>
                        <div>
                            <button type="reset" class="btn btn-secondary" style="font-size: 13px;">Cancelar</button>
                            <button type="submit" class="btn btn-success agregar" style=" font-size: 13px;" name="btn_guardar">Agregar</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="font-size: 13px;">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <?php
    $id = $_GET['id'];
    ?>
    <!-- Modal -->
    <div class="modal fade" id="editarModal" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar Operacion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php
                    $query = "SELECT * FROM tlb_operacion WHERE id_operacion = '$id'";
                    $query_rs = mysqli_query($conn, $query);

                    if ($query_rs->num_rows > 0) {
                        $filas = mysqli_fetch_array($query_rs);
                    ?>
                        <form action="./validacion.php" method="post">
                            <input type="text" name="id" id="" value="<?= $filas['id_operacion'] ?>">
                            <div class="form-group mb-2 input">
                                <label for="" class="label-form">Descripción</label>
                                <input type="text" class="form-control" name="descripcion" value="<?= $filas['descripcion'] ?>">
                            </div>
                            <div class="form-group mb-2 input">
                                <label for="" class="label-form">Monto</label>
                                <input type="number" class="form-control" name="monto" value="<?= $filas['monto'] ?>">
                            </div>
                            <div class="form-group mb-2 select">
                                <label for="" class="label-form">Tipo</label>
                                <select class="form-select form-select" aria-label=".form-select-sm example" name="gasto">
                                    <option selected>Eliga una opción</option>
                                    <?php
                                    $query_c = "SELECT * FROM tlb_gasto WHERE estado=1";
                                    $query_c_rs = mysqli_query($conn, $query_c);

                                    if ($query_c_rs->num_rows > 0) {
                                        foreach ($query_c_rs as $fila) {
                                    ?>
                                            <option <?= $filas['id_gasto'] == $fila['id_gasto'] ? "selected" : '' ?> value="<?= $fila['id_gasto'] ?>"><?= $fila['nombre_gasto'] ?></option>
                                    <?php

                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group mb-2 select">
                                <label for="" class="label-form">Categoria</label>
                                <select class="form-select form-select" aria-label=".form-select-sm example" name="categoria">
                                    <option selected>Eliga una opción</option>
                                    <?php
                                    $query_c = "SELECT * FROM tlb_categoria WHERE estado=1";
                                    $query_c_rs = mysqli_query($conn, $query_c);

                                    if ($query_c_rs->num_rows > 0) {
                                        foreach ($query_c_rs as $fila) {
                                    ?>
                                            <option <?= $filas['id_categoria'] == $fila['id_categoria'] ? "selected" : '' ?> value="<?= $fila['id_categoria'] ?>"><?= $fila['nombre_categoria'] ?></option>
                                    <?php

                                        }
                                    }
                                    ?>
                                </select>
                                <style>
                                    .select select:focus,
                                    .input input:focus {
                                        box-shadow: none;
                                        outline: none;
                                        border-color: black;
                                    }
                                </style>
                            </div>
                            <div class="form-group mb-2 input">
                                <label for="" class="label-form">Fecha</label>
                                <input type="date" class="form-control" name="fecha" value="<?= $filas['fecha'] ?>">
                            </div>
                            <div>
                                <button type="reset" class="btn btn-secondary" style="font-size: 13px;">Cancelar</button>
                                <button type="submit" class="btn btn-success agregar" style=" font-size: 13px;" name="btn_actualizar">Actualizar</button>
                            </div>
                        </form>
                    <?php
                    }
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="font-size: 13px;">Cerrar</button>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script>
        // Leer el ID de la URL y activar el modal correspondiente
        $(document).ready(function() {
            var urlParams = new URLSearchParams(window.location.search);
            var id = urlParams.get('id');
            if (id) {
                $('#editarModal').modal('show');
                var newUrl = '<?= $_SERVER['PHP_SELF'] ?>';
                history.replaceState(null, null, newUrl);
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="./bootstrap/js/bootstrap.js"></script>
</body>

</html>