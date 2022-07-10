<div id="sidebar" class="vh-100 py-4 pl-4">
    <div id="sidebarContent" class="h-100 w-100 sidebar">
        <div class="logo-details">
            <img src="<?= URL_IMG ?>logo.svg" style="max-width:100%; max-height:100%;" />
        </div>
        <ul class="nav-list">
            <li>
                <a href="<?= BASE_URL . '/' ?>">
                    <img src="<?= URL_IMG ?>home.svg" style="max-width:100%; max-height:100%;" />
                </a>
                <span class="tooltip">Dashboard</span>
            </li>
            <li>
                <a href="<?= BASE_URL . '/sales' ?>">
                    <img src="<?= URL_IMG ?>sales.svg" style="max-width:100%; max-height:100%;" />
                </a>
                <span class="tooltip">Vendas</span>
            </li>
            <li>
                <a href="<?= BASE_URL . '/clients' ?>">
                    <img src="<?= URL_IMG ?>user.svg" style="max-width:100%; max-height:100%;" />
                </a>
                <span class="tooltip">Clientes</span>
            </li>
            <li>
                <a href="<?= BASE_URL . '/products' ?>">
                    <img src="<?= URL_IMG ?>products.svg" style="max-width:100%; max-height:100%;" />
                </a>
                <span class="tooltip">Produtos</span>
            </li>
            <li>
                <a href="<?= BASE_URL . '/purchases' ?>">
                    <img src="<?= URL_IMG ?>purchases.svg" style="max-width:100%; max-height:100%;" />
                </a>
                <span class="tooltip">Compras</span>
            </li>
            <li>
                <a href="<?= BASE_URL . '/categories' ?>">
                    <img src="<?= URL_IMG ?>categories.svg" style="max-width:100%; max-height:100%;" />
                </a>
                <span class="tooltip">Categorias</span>
            </li>
            <li>
                <a href="<?= BASE_URL . '/logout' ?>">
                    <img src="<?= URL_IMG ?>logout.svg" style="max-width:100%; max-height:100%;" />
                </a>
                <span class="tooltip">Sair</span>
            </li>
            <jsp:include page="/componentes/sidebar_profile.jsp" />
        </ul>
    </div>
</div>