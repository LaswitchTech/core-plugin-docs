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
        .nav-prev-next {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        .edit-on-github {
            float: right;
            margin-bottom: 10px;
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
                    <h5 class="px-3"><?= $this->Locale->get('Documentation') ?></h5>
                    <?= $this->renderTOC($toc) ?>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 docs-content">
                <?php if(isset($can_edit) && $can_edit): ?>
                <a href="<?= $this->Locale->get('https://github.com/LaswitchTech/core/edit/master/') . $file_path ?>" 
                   class="btn btn-outline-primary btn-sm edit-on-github" 
                   target="_blank">
                    <?= $this->Locale->get('Edit on GitHub') ?>
                </a>
                <?php endif; ?>

                <div class="doc-content">
                    <?= $content ?>
                </div>

                <!-- Navigation -->
                <div class="nav-prev-next">
                    <div class="row">
                        <div class="col-md-6">
                            <?php if(!empty($siblings['prev'])): ?>
                            <a href="/docs/view?path=<?= urlencode($siblings['prev']['path']) ?>&file=<?= urlencode($siblings['prev']['filename']) ?>" class="btn btn-outline-secondary">
                                <?= $this->Locale->get('Previous') ?>: <?= $siblings['prev']['name'] ?>
                            </a>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 text-end">
                            <?php if(!empty($siblings['next'])): ?>
                            <a href="/docs/view?path=<?= urlencode($siblings['next']['path']) ?>&file=<?= urlencode($siblings['next']['filename']) ?>" class="btn btn-outline-secondary">
                                <?= $this->Locale->get('Next') ?>: <?= $siblings['next']['name'] ?>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
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