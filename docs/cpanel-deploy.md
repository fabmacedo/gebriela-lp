# Deploy no cPanel pelo Git Version Control

Este projeto deve ser publicado no document root do subdomínio:

`trabalhista.gabrielapitaadvogados.com.br`

O arquivo `.cpanel.yml` aponta o deploy para:

`$HOME/trabalhista.gabrielapitaadvogados.com.br/`

No cPanel, crie ou confirme o subdomínio em **Domains** e confira o **Document Root**. Se o caminho exibido for diferente, altere apenas a linha `DEPLOYPATH` no `.cpanel.yml`.

## Arquivo de banco

O arquivo `config/database.php` não deve ir para o Git nem ser sobrescrito pelo deploy. Ele fica como configuração local do ambiente.

No servidor, crie esse arquivo uma única vez dentro de:

`trabalhista.gabrielapitaadvogados.com.br/config/database.php`

Depois disso, o deploy continuará copiando o site sem substituir as credenciais do banco.

## Fluxo sugerido

1. Suba o repositório para o Git remoto.
2. No cPanel, abra **Files > Git Version Control**.
3. Crie ou clone o repositório.
4. Use **Pull or Deploy > Update from Remote** para puxar mudanças.
5. Use **Deploy HEAD Commit** para publicar no subdomínio.
