
    
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- CSS link -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">

    <!-- Bootstrap Icons -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
        <script src="<?= BASE_URL ?>/assets/js/script.js"></script>

    <nav class="topbar">

        <div class="d-flex align-items-center">

            <button
                class="menu-toggle"
                id="menuToggle"
                type="button"
            >
                <i class="bi bi-list"></i>
            </button>

            <div class="ms-2 d-none d-md-block">
                <span class="text-muted">
                    Dashboard
                </span>
            </div>
            

        </div>


        <!-- RIGHT TOPBAR -->

        <div class="d-flex align-items-center gap-3">

            <!-- Notification -->

            <button
                class="btn btn-light position-relative"
                type="button"
            >
                <i class="bi bi-bell fs-5"></i>

                <span
                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                >
                    3
                </span>
            </button>


            <!-- Profile -->

            <div class="dropdown">

                <button
                    class="btn btn-light dropdown-toggle d-flex align-items-center gap-2"
                    data-bs-toggle="dropdown"
                    type="button"
                >


                    <span class="admin-name">
                        <?php 
                            $ID = $_SESSION['id'];
                            $dt_user = mysqli_query($conn, "SELECT * FROM users WHERE id = '$ID'");
                            while ($user = mysqli_fetch_array($dt_user)) { ?>
                                <?php
                                echo $user['username'] ;
                            }
                        ?>
                    </span>

                </button>

                <ul class="dropdown-menu dropdown-menu-end shadow border-0">

                    <li>
                        <a
                            class="dropdown-item"
                            href="<?= BASE_URL ?>admin/profil.php"
                        >
                            <i class="bi bi-person me-2"></i>
                            Profil
                        </a>
                    </li>

                    <li>
                        <a
                            class="dropdown-item"
                            href="pengaturan.php"
                        >
                            <i class="bi bi-gear me-2"></i>
                            Pengaturan
                        </a>
                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <a
                            class="dropdown-item text-danger"
                            href="../auth/logout.php"
                        >
                            <i class="bi bi-box-arrow-right me-2"></i>
                            Logout
                        </a>
                    </li>

                </ul>

            </div>

        </div>

    </nav>
