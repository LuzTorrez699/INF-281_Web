<?php
    session_start();
    require "conexion.php";

    if(!isset($_SESSION['idUSUARIO'])){
        header("location: index.php");
    }

    $nombre = $_SESSION['nombre'];
    $nivel = $_SESSION['nivel'];
    $tipo_idtipo = $_SESSION['tipo_idtipo'];
?>
<?php include("template/cabecera.php");?>
<section class="vh-100 bg-image" style="background-image: url('https://images.unsplash.com/photo-1683620882714-e079c61d6bfe?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=735&q=80');">
    <div class="mask d-flex align-items-center h-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <div class="card shadow-lg border-0 rounded-lg mt-5">
                        <div class="card-header">
                            <h3 class="text-center font-weight-light my-4">Registro de Producto</h3>
                        </div>
                        <div class="card-body">
                            <form action="registrar_producto.php" method="post" enctype="multipart/form-data">
                                <!-- Nombre del Producto -->
                                <div class="form-floating mb-3">
                                    <input class="form-control" name="nombre_producto" id="nombre_producto" type="text" placeholder="Nombre del producto" required/>
                                    <label for="nombre_producto">Nombre del Producto</label>
                                </div>
                                <!-- Descripción del Producto -->
                                <div class="form-floating mb-3">
                                    <input class="form-control" name="descripcion_producto" id="descripcion_producto" type="text" placeholder="Descripción del producto" required/>
                                    <label for="descripcion_producto">Descripción del Producto</label>
                                </div>
                                <!-- Precio del Producto -->
                                <div class="form-floating mb-3">
                                    <input class="form-control" name="precio_producto" id="precio_producto" type="number" step="0.01" placeholder="Precio del producto" required/>
                                    <label for="precio_producto">Precio del Producto</label>
                                </div>
                                <!-- Stock del Producto -->
                                <div class="form-floating mb-3">
                                    <input class="form-control" name="stock_producto" id="stock_producto" type="number" placeholder="Cantidad en stock" required/>
                                    <label for="stock_producto">Cantidad en Stock</label>
                                </div>
                                <!-- Categoría del Producto -->
                                <div class="form-floating mb-3">
                                    <select class="form-control" name="categoria_producto" id="categoria_producto" required>
                                        <option value="">Seleccionar Categoría...</option>
                                        <?php
                                        $query = "SELECT id_categoria, descripcion FROM categoria";
                                        $result = mysqli_query($mysqli, $query);
                                        while($row = mysqli_fetch_array($result)){ ?>
                                        <option value="<?php echo $row['id_categoria'];?>"><?php echo $row['descripcion'];?></option>
                                        <?php } ?>
                                    </select>
                                    <label for="categoria_producto">Categoría del Producto</label>
                                </div>
                                
                                <!-- Foto del Producto -->
                                <div class="form-group mb-3">
                                    <label for="foto_producto">Adjuntar foto del producto</label>
                                    <input type="file" name="foto_producto" id="foto_producto" class="form-control-file" required>
                                    <p class="help-block">Seleccione una imagen para el producto. (MAX 64 KB)</p>
                                </div>
                                <!-- Botones -->
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="submit" value="registrar" class="btn btn-success">Registrar Producto</button>
                                    <a href="principal.php" class="btn btn-secondary">Cancelar</a>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer text-center py-3">
                            <div class="small"><a href="principal.php">Volver al Inicio</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php include("template/pie.php");?>
