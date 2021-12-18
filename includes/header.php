<header class="bg-dark bg-gradient">
    <div class="container-xl h-100 d-flex align-items-center">
        <a class="d-flex align-items-center text-decoration-none" href="<?php echo PG_HOME; ?>"><i class="fas fa-blog fs-2 text-white"></i><h1 class="fs-2 ms-2 my-0 text-white">Web Blog</h1></a>
        <?php if(isset($_SESSION['username'])) : ?>
            <a class="d-flex align-items-center btn ms-auto text-warning" href="<?php echo PG_ADMIN; ?>"><i class="fas fa-user-cog me-2"></i>Admin</a>
            <a class="d-flex align-items-center btn text-warning" href="<?php echo PG_LOGOUT; ?>"><i class="fas fa-sign-in-alt me-2"></i>Log Out</a>
        <?php else : ?>
            <a class="d-flex align-items-center btn ms-auto text-warning" href="<?php echo PG_LOGIN; ?>"><i class="fas fa-sign-in-alt me-2"></i>Log In</a>
        <?php endif; ?>
    </div>
</header>