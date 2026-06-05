# Regra de texto e acentuação

Antes de entregar qualquer nova tela, post ou texto do site:

- Conferir se o texto está em português com acentuação correta.
- Verificar se não existem sinais de codificação quebrada, como `Ã`, `Â`, `�` ou sequências estranhas.
- Manter slugs sem acentos, em minúsculas e separados por hífen.
- Revisar palavras comuns: `análise`, `jurídico`, `configurações`, `solicitação`, `endereço`, `avaliações`, `conteúdo`, `prévia`, `segurança`, `ética`, `ação`, `dúvidas`.
- Rodar a checagem textual local antes de finalizar alterações relevantes:

```powershell
php scripts/check_text_quality.php
```
