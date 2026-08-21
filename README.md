# ☁️ Cumulus

Sistema web para consulta de informações meteorológicas, desenvolvido com **PHP, MySQL, HTML e CSS**.

O Cumulus permite pesquisar cidades, consultar condições climáticas e salvar localidades favoritas para facilitar o acesso posteriormente.

## ✨ Funcionalidades

* 👤 Cadastro e login de usuários
* 🔎 Pesquisa de cidades
* 🌦️ Consulta de informações meteorológicas
* 📍 Visualização dos detalhes do clima
* ⭐ Adição e remoção de cidades favoritas
* 🔗 Integração com API meteorológica
* 🗄️ Persistência de dados com MySQL
* 🔐 Gerenciamento de sessões

## 🛠️ Tecnologias

**Back-end**

* PHP
* MySQL
* SQL

**Front-end**

* HTML5
* CSS3

**Integrações**

* API meteorológica
* Requisições HTTP

## 📂 Estrutura

```text
Cumulus/
├── adicionar_favorito.php
├── api_clima.php
├── cadastro.php
├── config.php
├── database.sql
├── detalhes_clima.php
├── favoritos.php
├── Home.css
├── home.php
├── index.php
├── logout.php
├── remover_favorito.php
└── search_proxy.php
```

## 🚀 Como executar

### Requisitos

* PHP 8+
* MySQL
* Apache
* XAMPP, WAMP ou ambiente equivalente

### 1. Banco de dados

Crie um banco de dados MySQL e execute o arquivo:

```text
database.sql
```

### 2. Configuração

Configure as credenciais do banco no arquivo:

```text
config.php
```

Caso seja utilizada uma chave de API, configure-a conforme as variáveis utilizadas no projeto.

> ⚠️ Não publique senhas, credenciais ou chaves de API no repositório.

### 3. Executar

Coloque o projeto na pasta `htdocs` do XAMPP:

```text
C:\xampp\htdocs\cumulus
```

Inicie o **Apache** e o **MySQL** e acesse:

```text
http://localhost/cumulus
```

## 🔄 Fluxo da aplicação

```text
Cadastro / Login
       ↓
     Home
       ↓
Pesquisa de cidade
       ↓
API Meteorológica
       ↓
Dados do clima
       ↓
Detalhes da cidade
       ↓
Adicionar aos favoritos
```

## 🎯 Objetivo

O projeto foi desenvolvido para praticar conceitos de **desenvolvimento web, integração com APIs, autenticação, banco de dados e construção de aplicações utilizando PHP**.

## 🔮 Melhorias futuras

* Previsão para vários dias
* Previsão por hora
* Geolocalização
* Histórico de pesquisas
* Tema claro/escuro
* Melhorias de responsividade
* Testes automatizados
* Deploy em produção

## 👨‍💻 Desenvolvedor

**Gustavo Fagundes**

Graduando em Ciência da Computação pela **PUCPR**, com interesse em desenvolvimento Full Stack, APIs, Inteligência Artificial, LLMs e automação.

---

**Status:** 🟢 Em desenvolvimento
