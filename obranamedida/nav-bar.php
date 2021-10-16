<nav class="nav-bar">
            <li><a class="btn-nav" href="index.php">
                <img src="assets/jornal.png">
            </a></li>
            <li><a class="btn-nav" href="projetos.php">
                <img src="assets/category.png">
            </a></li>
            <li><a class="btn-nav" href="mensagens.php">
                <img src="assets/message.png">
            </a></li>
            <li><a class="btn-nav" href="perfil.php">
                <img src="assets/profile.png">
            </a></li>
            <?php 
                if(isset($_SESSION["user"])){
                    ?>
                    <div id="session">
                        <li>
                            <a href="log-out.php">
                                <img src="assets/log-out.png">
                            </a>
                        </li>
                    </div>
                    <?php
                } else {
            ?>
            <!-- Cadastro / Login -->
            <div id="session">
                <li><a href="cadastro.php">
                    <img src="assets/log-in.png">
                </a></li>
            </div>
            <?php
            }
            ?>
</nav>

