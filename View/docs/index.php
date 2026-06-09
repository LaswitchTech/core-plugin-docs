<!DOCTYPE html>
<html lang="<?= $this->Locale->get('lang') ?>">
<head>
    <meta charset="UTF-8">
    <title><?= $this->Locale->get($title) ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom Styles -->
    <style>
        .docs-sidebar {
            height: calc(100vh - 56px);
            overflow-y: auto;
            position: fixed;
            left: 0;
            top: 56px;
        }
        .docs-content {
            margin-left: 250px;
            padding: 20px;
        }
        .nav-link.active {
            font-weight: bold;
        }
        .toc-item {
            margin-bottom: 5px;
        }
        .toc-item a {
            text-decoration: none;
            color: #333;
            display: block;
            padding: 5px 10px;
            border-radius: 3px;
        }
        .toc-item a:hover {
            background-color: #f0f0f0;
        }
        .toc-item.active a {
            background-color: #0d6efd;
            color: white;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="/docs"><?= $this->Locale->get('Documentation') ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar docs-sidebar">
                <div class="position-sticky pt-3">
                    <h5 class="px-3"><?= $this->Locale->get('Table of Contents') ?></h5>
                    <?= $this->renderTOC($toc) ?>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 docs-content">
                <h1><?= $this->Locale->get('Documentation') ?></h1>
                <p><?= $this->Locale->get('Welcome to the documentation system.') ?></p>
                <p><?= $this->Locale->get('Browse through the table of contents on the left to find documentation for different parts of the framework.') ?></p>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="/assets/js/jquery-3.6.0.min.js"></script>
    <script src="/assets/js/bootstrap.bundle.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Set active menu items based on URL
            var currentPath = window.location.pathname;
            $('.toc-item a[href="' + currentPath + '"]').addClass('active').parent().addClass('active');
        });
    </script>
</body>
</html>