<div id="layoutSidenav_nav" class="vh-100 py-4 pl-4">
    <div id="sidebarContent" class="h-100 w-100 sidebar">
        <ul class="nav-list">
            <li>
                <a href="<?= BASE_URL . '/' ?>">
                    <img src="<?= URL_IMG ?>home.svg" style="padding: 5px" />
                </a>
                <span class="tooltip">Dashboard</span>
            </li>
            <li>
                <a href="<?= BASE_URL . '/sales' ?>">
                    <img src="<?= URL_IMG ?>sales.svg" />
                </a>
                <span class="tooltip">Vendas</span>
            </li>
            <li>
                <a href="<?= BASE_URL . '/customers' ?>">
                    <img src="<?= URL_IMG ?>user.svg" style="padding: 8px" />
                </a>
                <span class="tooltip">Clientes</span>
            </li>
            <li>
                <a href="<?= BASE_URL . '/products' ?>">
                    <img src="<?= URL_IMG ?>products.svg" />
                </a>
                <span class="tooltip">Produtos</span>
            </li>
            <li>
                <a href="<?= BASE_URL . '/categories' ?>">
                    <img src="<?= URL_IMG ?>categories.svg" />
                </a>
                <span class="tooltip">Categorias</span>
            </li>
            <li>
                <a href="<?= BASE_URL . '/providers' ?>">
                    <img src="<?= URL_IMG ?>providers.svg" />
                </a>
                <span class="tooltip">Fornecedores</span>
            </li>
            <li>
                <a href="<?= BASE_URL . '/purchases' ?>">
                    <img src="<?= URL_IMG ?>purchases.svg" style="padding: 5px" />
                </a>
                <span class="tooltip">Compras</span>
            </li>
            <li>
                <a href="<?= BASE_URL . '/logout' ?>">
                    <img src="<?= URL_IMG ?>logout.svg" style="padding: 5px" />
                </a>
                <span class="tooltip">Sair</span>
            </li>
            <jsp:include page="/componentes/sidebar_profile.jsp" />
        </ul>
    </div>
</div>