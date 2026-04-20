document.addEventListener('DOMContentLoaded', () => {
    const currentYearTargets = document.querySelectorAll('[data-current-year]');
    const shell = document.body;
    const sidebar = document.getElementById('appSidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const sidebarToggles = document.querySelectorAll('[data-sidebar-toggle]');
    const sidebarCloseButtons = document.querySelectorAll('[data-sidebar-close]');
    const sidebarCollapseButtons = document.querySelectorAll('[data-sidebar-collapse]');
    const topbar = document.querySelector('[data-topbar]');
    const userMenuToggle = document.querySelector('[data-user-menu-toggle]');
    const userMenu = document.querySelector('[data-user-menu]');
    const menuDropdownToggles = document.querySelectorAll('[data-menu-dropdown-toggle]');
    const permissionMaster = document.querySelector('[data-permission-master]');
    const permissionSearch = document.querySelector('[data-permission-search]');
    const permissionFeatureCards = document.querySelectorAll('[data-permission-feature]');
    const permissionGroupToggles = document.querySelectorAll('[data-permission-group-toggle]');
    const permissionCheckboxes = document.querySelectorAll('[data-permission-checkbox]');
    const roleSearch = document.querySelector('[data-role-search]');
    const roleRows = document.querySelectorAll('[data-role-row]');

    currentYearTargets.forEach((target) => {
        target.textContent = new Date().getFullYear().toString();
    });

    const updateTopbarState = () => {
        topbar?.classList.toggle('is-scrolled', window.scrollY > 8);
    };

    updateTopbarState();
    window.addEventListener('scroll', updateTopbarState, { passive: true });

    const syncPermissionToggles = () => {
        permissionFeatureCards.forEach((card) => {
            const groupToggle = card.querySelector('[data-permission-group-toggle]');
            const groupCheckboxes = card.querySelectorAll('[data-permission-checkbox]');
            const checkedCount = Array.from(groupCheckboxes).filter((checkbox) => checkbox.checked).length;

            if (!groupToggle || groupCheckboxes.length === 0) {
                return;
            }

            groupToggle.checked = checkedCount === groupCheckboxes.length;
            groupToggle.indeterminate = checkedCount > 0 && checkedCount < groupCheckboxes.length;
        });

        if (!permissionMaster || permissionCheckboxes.length === 0) {
            return;
        }

        const checkedCount = Array.from(permissionCheckboxes).filter((checkbox) => checkbox.checked).length;
        permissionMaster.checked = checkedCount === permissionCheckboxes.length;
        permissionMaster.indeterminate = checkedCount > 0 && checkedCount < permissionCheckboxes.length;
    };

    permissionGroupToggles.forEach((toggle) => {
        toggle.addEventListener('change', () => {
            const card = toggle.closest('[data-permission-feature]');

            card?.querySelectorAll('[data-permission-checkbox]').forEach((checkbox) => {
                checkbox.checked = toggle.checked;
            });

            syncPermissionToggles();
        });
    });

    permissionCheckboxes.forEach((checkbox) => {
        checkbox.addEventListener('change', syncPermissionToggles);
    });

    permissionMaster?.addEventListener('change', () => {
        permissionCheckboxes.forEach((checkbox) => {
            checkbox.checked = permissionMaster.checked;
        });

        syncPermissionToggles();
    });

    permissionSearch?.addEventListener('input', () => {
        const keyword = permissionSearch.value.trim().toLowerCase();

        permissionFeatureCards.forEach((card) => {
            card.hidden = keyword !== '' && !card.textContent.toLowerCase().includes(keyword);
        });
    });

    roleSearch?.addEventListener('input', () => {
        const keyword = roleSearch.value.trim().toLowerCase();

        roleRows.forEach((row) => {
            row.hidden = keyword !== '' && !row.textContent.toLowerCase().includes(keyword);
        });
    });

    syncPermissionToggles();

    if (!sidebar) {
        return;
    }

    const closeUserMenu = () => {
        userMenu?.classList.remove('show');
        userMenuToggle?.setAttribute('aria-expanded', 'false');
    };

    const isMobile = () => window.matchMedia('(max-width: 992px)').matches;

    const openSidebar = () => {
        sidebar.classList.add('mobile-open');
        sidebarOverlay?.classList.add('mobile-open');
    };

    const closeSidebar = () => {
        sidebar.classList.remove('mobile-open');
        sidebarOverlay?.classList.remove('mobile-open');
    };

    const expandCollapsedSidebar = () => {
        if (!isMobile() && shell.classList.contains('layout-menu-collapsed')) {
            shell.classList.add('layout-menu-hover');
        }
    };

    const shrinkHoveredSidebar = () => {
        shell.classList.remove('layout-menu-hover');
    };

    const toggleSidebar = () => {
        if (isMobile()) {
            sidebar.classList.contains('mobile-open') ? closeSidebar() : openSidebar();
            return;
        }

        shrinkHoveredSidebar();
        shell.classList.toggle('layout-menu-collapsed');
        localStorage.setItem('ims-sidebar-collapsed', shell.classList.contains('layout-menu-collapsed') ? '1' : '0');
    };

    if (localStorage.getItem('ims-sidebar-collapsed') === '1' && !isMobile()) {
        shell.classList.add('layout-menu-collapsed');
    }

    sidebarToggles.forEach((button) => {
        button.addEventListener('click', toggleSidebar);
    });

    sidebarCollapseButtons.forEach((button) => {
        button.addEventListener('click', toggleSidebar);
    });

    userMenuToggle?.addEventListener('click', (event) => {
        event.stopPropagation();
        const isOpen = userMenu?.classList.toggle('show') ?? false;
        userMenuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });

    userMenu?.addEventListener('click', (event) => {
        event.stopPropagation();
    });

    menuDropdownToggles.forEach((button) => {
        button.addEventListener('click', () => {
            const submenu = button.nextElementSibling;

            if (!submenu) {
                return;
            }

            const isOpen = submenu.classList.toggle('show');
            button.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
    });

    sidebarCloseButtons.forEach((button) => {
        button.addEventListener('click', closeSidebar);
    });

    sidebar.addEventListener('mouseenter', expandCollapsedSidebar);
    sidebar.addEventListener('mouseleave', shrinkHoveredSidebar);

    window.addEventListener('resize', () => {
        if (!isMobile()) {
            closeSidebar();
        }

        shrinkHoveredSidebar();
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeSidebar();
            closeUserMenu();
        }
    });

    document.addEventListener('click', closeUserMenu);

    window.toggleSidebar = toggleSidebar;
});
