<!DOCTYPE html>
<html>
    <head>
        <?php 
            require_once __DIR__ . '/functions/verify_login.php';
            require_once __DIR__ . '/functions/stats.php';

            $conn = new MySqlConnection();
            $conn->start_transaction();
            $stats = Statistics::get($conn);
        ?>
        <!--title-->
        <title>Inicio - CheesePay</title>
        <link rel="icon" type="image/png" href="favicon.png">
        <!--javascript-->
        <script src="js/common.js"></script>
        <script src="js/fontawesome/solid.js"></script>
        <!--stylesheets-->
        <link href="css/style.css" rel="stylesheet" />
        <link href="css/menu.css" rel="stylesheet" />
        <link href="css/header.css" rel="stylesheet" />
        <link href="css/controls.css" rel="stylesheet" />
        <link href="css/alerts.css" rel="stylesheet" />
        <link href="css/theme.css" rel="stylesheet" />
        <link href="css/dialogs.css" rel="stylesheet" />
        <link href="css/index.css" rel="stylesheet" />
        <link href="css/fontawesome/fontawesome.css" rel="stylesheet" />
        <link href="css/fontawesome/solid.css" rel="stylesheet" />
        <!--metadata-->
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <meta charset="utf-8"/>
    </head>
    <body>
        <header>
            <div class="header-left">
                <div class="header-menu">
                    <i id="toggle-menu" class="fas fa-bars"></i>
                </div>
                <a class="header-logo" href="index.php">
                    <img src="images/logo.png">
                </a>
            </div>
            <div class="header-right">
                <div class="user-photo">
                    <img>
                </div>
                <div class="user-icons">
                    <a href="user_panel.php">
                        <i class="fas fa-cog"></i>
                    </a>
                    <a href="actions/sign_out.php">
                        <i class="fas fa-sign-out-alt" ></i>
                    </a>
                </div>
            </div>
        </header>
        <div id="menu" class="show">
            <a class="menu-item" href="index.php">
                <div class="menu-elements">
                    <div class="menu-icon">
                        <i class="fas fa-home"></i>
                    </div>
                    <label>Página principal</label>
                </div>
            </a>
            <a class="menu-item" href="registration_panel.php">
                <div class="menu-elements">
                    <div class="menu-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <label>Registrar alumno</label>
                </div>
            </a>
            <a class="menu-item" href="student_panel.php">
                <div class="menu-elements">
                    <div class="menu-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <label>Consultar alumno</label>
                </div>
            </a>
            <a class="menu-item" href="group_query_panel.php">
                <div class="menu-elements">
                    <div class="menu-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <label>Consultar grupos</label>
                </div>
            </a>
            <a class="menu-item" href="fee_query_panel.php">
                <div class="menu-elements">
                    <div class="menu-icon">
                        <i class="fas fa-search-dollar"></i>
                    </div>
                    <label>Consultar cuotas</label>
                </div>
            </a>
            <a class="menu-item" href="control_panel.php" style="display: none;">
                <div class="menu-elements">
                    <div class="menu-icon">
                        <i class="fas fa-sliders-h"></i>
                    </div>
                    <label>Panel de control</label>
                </div>
            </a>
        </div>
        <div id="content">
            <h1>Inicio</h1>
            <h2>Bienvenido a CheesePay, ¡la plataforma de pago más eficiente!</h2>
            <div id="main-review">
                <p>
                    Aquí podrás administrar los diferentes cobros y movimientos financieros relacionados con la
                    matrícula escolar de tu colegio, de una manera rápida y sencilla, para la agilización de tu
                    proceso de pago.
                </p>
                <div class="stats-container">
                    <div class="col-4 col-m-6 col-s-12">
                        <div id="student-count" class="card">
                            <p class="stats-header"><?php echo $stats->get_active_student_count(); ?> alumnos activos</p>
                            <p class="stats-subheader"><?php echo $stats->get_student_count(); ?> registrados</p>
                        </div>
                    </div>
                    <div class="col-4 col-m-6 col-s-12">
                        <div id="payment-count" class="card">
                            <p class="stats-header"><?php echo $stats->get_current_week_payment_count(); ?> pagos hechos en la semana actual</p>
                            <p class="stats-subheader"><?php echo $stats->get_previous_week_payment_count(); ?> la semana pasada</p>
                        </div>
                    </div>
                    <div class="col-4 col-m-6 col-s-12">
                        <div id="payments-due-count" class="card">
                            <p class="stats-header"><?php echo $stats->get_students_with_payments_due_count(); ?> alumnos con pagos pendientes</p>
                        </div>
                    </div>
                    <div class="col-4 col-m-6 col-s-12">
                        <div id="events-count" class="card">
                            <p class="stats-header"><?php echo $stats->get_special_event_count(); ?> evento(s) próximos</p>
                        </div>
                    </div>
                    <div class="col-4 col-m-6 col-s-12">
                        <div id="groups" class="card">
                            <p class="stats-header"><?php echo $stats->get_group_count(); ?> grupos</p>
                            <p class="stats-subheader"><?php echo $stats->get_education_level_count(); ?> niveles educativos</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
