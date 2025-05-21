// Populate the sidebar
//
// This is a script, and not included directly in the page, to control the total size of the book.
// The TOC contains an entry for each page, so if each page includes a copy of the TOC,
// the total size of the page becomes O(n**2).
class MDBookSidebarScrollbox extends HTMLElement {
    constructor() {
        super();
    }
    connectedCallback() {
        this.innerHTML = '<ol class="chapter"><li class="chapter-item expanded affix "><a href="guia.html">Guia Básico de Conteúdos para a Disciplina de Engenharia de Software</a></li><li class="chapter-item expanded affix "><a href="00-prefacio.html">Prefácio</a></li><li class="chapter-item expanded affix "><a href="intro.html">Introdução</a></li><li class="chapter-item expanded "><a href="t01-1.0-git-github.html"><strong aria-hidden="true">1.</strong> Capítulo 1 - Utilizando Git e GitHub</a></li><li><ol class="section"><li class="chapter-item expanded "><a href="t01-1.1-git-github.html"><strong aria-hidden="true">1.1.</strong> Principais comandos Git</a></li><li class="chapter-item expanded "><a href="t01-1.2-git-github.html"><strong aria-hidden="true">1.2.</strong> Referências do capítulo</a></li></ol></li><li class="chapter-item expanded "><a href="t02-2.0-code-review.html"><strong aria-hidden="true">2.</strong> Capítulo 2 - Code Review e Pull Request</a></li><li><ol class="section"><li class="chapter-item expanded "><a href="t02-2.1-code-review.html"><strong aria-hidden="true">2.1.</strong> Afinal, o que é Pull Request?</a></li><li class="chapter-item expanded "><a href="t02-2.2-code-review.html"><strong aria-hidden="true">2.2.</strong> Criando um Pull Request no GitHub</a></li><li class="chapter-item expanded "><a href="t02-2.3-code-review.html"><strong aria-hidden="true">2.3.</strong> Code Review</a></li><li class="chapter-item expanded "><a href="t02-2.4-code-review.html"><strong aria-hidden="true">2.4.</strong> Como realizar um Code Review eficaz no GitHub</a></li><li class="chapter-item expanded "><a href="t02-2.5-code-review.html"><strong aria-hidden="true">2.5.</strong> Referências do capítulo</a></li></ol></li><li class="chapter-item expanded "><a href="t03-3.0-gitflow-html-css.html"><strong aria-hidden="true">3.</strong> Capítulo 3 - Aplicando o GitFlow no Git e GitHub com html e css</a></li><li><ol class="section"><li class="chapter-item expanded "><a href="t03-3.1-gitflow-html-css.html"><strong aria-hidden="true">3.1.</strong> Oque é o GitFlow</a></li><li class="chapter-item expanded "><a href="t03-3.2-gitflow-html-css.html"><strong aria-hidden="true">3.2.</strong> Como implementar o GitFlow no git e GitHub</a></li><li class="chapter-item expanded "><a href="t03-3.3-gitflow-html-css.html"><strong aria-hidden="true">3.3.</strong> Aplicando o GitFlow em um projeto com HTML e CSS</a></li><li class="chapter-item expanded "><a href="t03-3.4-gitflow-html-css.html"><strong aria-hidden="true">3.4.</strong> Boas Práticas para GitFlow com HTML e CSS</a></li><li class="chapter-item expanded "><a href="t03-3.5-gitflow-html-css.html"><strong aria-hidden="true">3.5.</strong> Documentação</a></li></ol></li><li class="chapter-item expanded "><a href="t04-4.0-php-gitflow.html"><strong aria-hidden="true">4.</strong> Capítulo 4 - Introdução ao PHP Básico aplicando GitFlow no GitHub</a></li><li><ol class="section"><li class="chapter-item expanded "><a href="t04-4.1-php-gitflow.html"><strong aria-hidden="true">4.1.</strong> Como instalar o PHP no Window</a></li><li class="chapter-item expanded "><a href="t04-4.2-php-gitflow.html"><strong aria-hidden="true">4.2.</strong> O que é XAMPP?</a></li><li><ol class="section"><li class="chapter-item expanded "><a href="t04-4.2.1-php-gitflow.html"><strong aria-hidden="true">4.2.1.</strong> Exemplo Prático: Configurando ambiente XAMPP</a></li></ol></li><li class="chapter-item expanded "><a href="t04-4.3-php-gitflow.html"><strong aria-hidden="true">4.3.</strong> O que é Docker?</a></li><li><ol class="section"><li class="chapter-item expanded "><a href="t04-4.3.1-php-gitflow.html"><strong aria-hidden="true">4.3.1.</strong> Exemplo Prático: Configurando ambiente Docker</a></li></ol></li><li class="chapter-item expanded "><a href="t04-4.4-php-gitflow.html"><strong aria-hidden="true">4.4.</strong> Conex - tópico 04</a></li><li><ol class="section"><li class="chapter-item expanded "><a href="t04-4.4.1-php-gitflow.html"><strong aria-hidden="true">4.4.1.</strong> Cadastro de usuários no sistema</a></li></ol></li><li class="chapter-item expanded "><a href="t04-4.5-php-gitflow.html"><strong aria-hidden="true">4.5.</strong> Boas Práticas para Desenvolvimento PHP com GitFlow</a></li><li class="chapter-item expanded "><a href="t04-4.6-php-gitflow.html"><strong aria-hidden="true">4.6.</strong> Documentação</a></li></ol></li><li class="chapter-item expanded "><a href="t05-5.0-session-php.html"><strong aria-hidden="true">5.</strong> Capítulo 5 - Sessões de usuário no navegador</a></li><li><ol class="section"><li class="chapter-item expanded "><a href="t05-5.1-session-php.html"><strong aria-hidden="true">5.1.</strong> Sessões em PHP</a></li><li class="chapter-item expanded "><a href="t05-5.2-session-php.html"><strong aria-hidden="true">5.2.</strong> Conex - tópico 05</a></li><li><ol class="section"><li class="chapter-item expanded "><a href="t05-5.2.1-session-php.html"><strong aria-hidden="true">5.2.1.</strong> Login no sistema</a></li></ol></li><li class="chapter-item expanded "><a href="t05-5.3-session-php.html"><strong aria-hidden="true">5.3.</strong> Boas Práticas para Sessões em PHP</a></li><li class="chapter-item expanded "><a href="t05-5.4-session-php.html"><strong aria-hidden="true">5.4.</strong> Documentação</a></li></ol></li><li class="chapter-item expanded "><a href="t06-6.0-crud-php.html"><strong aria-hidden="true">6.</strong> Capítulo 6 - CRUD com PHP</a></li><li><ol class="section"><li class="chapter-item expanded "><a href="t06-6.1-crud-php.html"><strong aria-hidden="true">6.1.</strong> Camadas da aplicação</a></li><li class="chapter-item expanded "><a href="t06-6.2-crud-php.html"><strong aria-hidden="true">6.2.</strong> Conex - tópico 06</a></li><li><ol class="section"><li class="chapter-item expanded "><a href="t06-6.2.2-crud-php.html"><strong aria-hidden="true">6.2.1.</strong> Criação de posts no feed</a></li><li class="chapter-item expanded "><a href="t06-6.2.3-crud-php.html"><strong aria-hidden="true">6.2.2.</strong> Exibição dos posts de todos os usuários no feed</a></li><li class="chapter-item expanded "><a href="t06-6.2.1-crud-php.html"><strong aria-hidden="true">6.2.3.</strong> Atualização de perfil do usuário</a></li><li class="chapter-item expanded "><a href="t06-6.2.4-crud-php.html"><strong aria-hidden="true">6.2.4.</strong> Exibir perfil do usuário </a></li><li class="chapter-item expanded "><a href="t06-6.2.5-crud-php.html"><strong aria-hidden="true">6.2.5.</strong> Exibir no perfil do usuário suas respectivas postagens</a></li></ol></li></ol></li></ol>';
        // Set the current, active page, and reveal it if it's hidden
        let current_page = document.location.href.toString();
        if (current_page.endsWith("/")) {
            current_page += "index.html";
        }
        var links = Array.prototype.slice.call(this.querySelectorAll("a"));
        var l = links.length;
        for (var i = 0; i < l; ++i) {
            var link = links[i];
            var href = link.getAttribute("href");
            if (href && !href.startsWith("#") && !/^(?:[a-z+]+:)?\/\//.test(href)) {
                link.href = path_to_root + href;
            }
            // The "index" page is supposed to alias the first chapter in the book.
            if (link.href === current_page || (i === 0 && path_to_root === "" && current_page.endsWith("/index.html"))) {
                link.classList.add("active");
                var parent = link.parentElement;
                if (parent && parent.classList.contains("chapter-item")) {
                    parent.classList.add("expanded");
                }
                while (parent) {
                    if (parent.tagName === "LI" && parent.previousElementSibling) {
                        if (parent.previousElementSibling.classList.contains("chapter-item")) {
                            parent.previousElementSibling.classList.add("expanded");
                        }
                    }
                    parent = parent.parentElement;
                }
            }
        }
        // Track and set sidebar scroll position
        this.addEventListener('click', function(e) {
            if (e.target.tagName === 'A') {
                sessionStorage.setItem('sidebar-scroll', this.scrollTop);
            }
        }, { passive: true });
        var sidebarScrollTop = sessionStorage.getItem('sidebar-scroll');
        sessionStorage.removeItem('sidebar-scroll');
        if (sidebarScrollTop) {
            // preserve sidebar scroll position when navigating via links within sidebar
            this.scrollTop = sidebarScrollTop;
        } else {
            // scroll sidebar to current active section when navigating via "next/previous chapter" buttons
            var activeSection = document.querySelector('#sidebar .active');
            if (activeSection) {
                activeSection.scrollIntoView({ block: 'center' });
            }
        }
        // Toggle buttons
        var sidebarAnchorToggles = document.querySelectorAll('#sidebar a.toggle');
        function toggleSection(ev) {
            ev.currentTarget.parentElement.classList.toggle('expanded');
        }
        Array.from(sidebarAnchorToggles).forEach(function (el) {
            el.addEventListener('click', toggleSection);
        });
    }
}
window.customElements.define("mdbook-sidebar-scrollbox", MDBookSidebarScrollbox);
