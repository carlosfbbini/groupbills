# GroupBills

Sistema de gestão de despesas empresariais em Laravel.

## Funcionalidades implementadas

- Autenticação nativa (cadastro, login e logout)
- CRUD de:
  - Grupos
  - Empresas (vinculadas a grupos)
  - Despesas (fatura, parcela, valor, vencimento, status e data de pagamento)
- Relatórios:
  - Boletos a vencer no dia
  - Boletos vencidos
  - Histórico de pagamentos

## Stack

- Laravel 12
- PostgreSQL (ou SQLite para desenvolvimento/testes)
- TailwindCSS

## Como executar

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

## Testes

```bash
php artisan test
```
