# 🏛️ Sistema de Gestão de Imóveis e IPTU

Um sistema web completo desenvolvido para simular o painel administrativo de uma prefeitura municipal, focado na gestão de contribuintes, registro de imóveis e emissão de guias de IPTU.

## 🚀 Funcionalidades
* **Dashboard Interativo:** Mapa integrado mostrando a região do município.
* **Gestão de Imóveis:** Cadastro, consulta, edição e exclusão de registros imobiliários.
* **Emissão de Guias:** Geração de guia de IPTU pronta para impressão (com código de barras simulado e formatação limpa via CSS `@media print`).
* **Controle de Acesso:** Sistema de login seguro com criação automática de usuário padrão.

## 🛠️ Tecnologias Utilizadas
* **Front-end:** HTML5, CSS3, Bootstrap 5, FontAwesome (Ícones).
* **Back-end:** PHP 8+.
* **Banco de Dados:** MySQL (comunicação via PDO).

## ⚙️ Como rodar o projeto na sua máquina

1. **Clone o repositório:**
   Baixe os arquivos para o seu computador.

2. **Configure o Banco de Dados:**
   * Crie um banco de dados no seu MySQL.
   * Configure as credenciais de acesso no arquivo `config.php` (usuário, senha e nome do banco).
   * *Nota: O sistema possui uma inteligência que cria a tabela de usuários automaticamente no primeiro acesso.*

3. **Inicie o Servidor PHP:**
   Abra o terminal na pasta do projeto e rode o servidor embutido do PHP:
   `php -S localhost:8000`

4. **Acesse no Navegador:**
   Abra o endereço `http://localhost:8000`.

## 🔐 Dados de Acesso Padrão
Para facilitar os testes, caso a tabela de usuários esteja vazia, o sistema gera automaticamente o seguinte acesso:

* **Usuário:** `admin`
* **Senha:** `123456`

---
*Desenvolvido como projeto de estudo e portfólio.*