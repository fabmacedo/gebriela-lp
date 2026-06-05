<?php if (current_admin()): ?>
    <footer class="admin-footer">
        <div class="admin-footer-inner">
            <span class="admin-footer-brand">
                Feito com
                <?php echo ph_icon('heart', 'admin-footer-heart', 'aria-label="amor" role="img"'); ?>
                pela <strong>Meraki</strong>
                <span class="admin-signature-prefix">by</span>
                <span class="admin-signature">Fabricio Macedo</span>
            </span>
            <span class="admin-version">Ver. 1.0.1</span>
        </div>
    </footer>
<?php endif; ?>
<script>
    (() => {
        const eyeIcon = '<i class="ph-light ph-eye" aria-hidden="true"></i>';
        const eyeOffIcon = '<i class="ph-light ph-eye-slash" aria-hidden="true"></i>';

        document.querySelectorAll('input[type="password"]').forEach((input) => {
            if (input.closest('.password-field')) return;

            const wrapper = document.createElement('div');
            wrapper.className = 'password-field';
            input.parentNode.insertBefore(wrapper, input);
            wrapper.appendChild(input);

            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'password-toggle';
            button.setAttribute('aria-label', 'Mostrar senha');
            button.setAttribute('title', 'Mostrar senha');
            button.innerHTML = eyeIcon;

            button.addEventListener('click', () => {
                const isPassword = input.type === 'password';
                input.type = isPassword ? 'text' : 'password';
                button.innerHTML = isPassword ? eyeOffIcon : eyeIcon;
                button.setAttribute('aria-label', isPassword ? 'Ocultar senha' : 'Mostrar senha');
                button.setAttribute('title', isPassword ? 'Ocultar senha' : 'Mostrar senha');
                input.focus();
            });

            wrapper.appendChild(button);
        });
    })();

    (() => {
        const closeAllSelects = (except = null) => {
            document.querySelectorAll('.select-field.is-open').forEach((field) => {
                if (field !== except) field.classList.remove('is-open');
            });
        };

        document.querySelectorAll('select').forEach((select) => {
            if (select.closest('.select-field')) return;

            const wrapper = document.createElement('div');
            wrapper.className = 'select-field';
            select.parentNode.insertBefore(wrapper, select);
            wrapper.appendChild(select);

            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'custom-select-button';
            button.setAttribute('aria-haspopup', 'listbox');
            button.setAttribute('aria-expanded', 'false');

            const label = document.createElement('span');
            label.className = 'custom-select-label';
            const caret = document.createElement('i');
            caret.className = 'custom-select-caret';
            caret.classList.add('ph-light', 'ph-caret-down');
            caret.setAttribute('aria-hidden', 'true');
            button.append(label, caret);

            const optionsList = document.createElement('div');
            optionsList.className = 'custom-select-options';
            optionsList.setAttribute('role', 'listbox');

            const update = () => {
                const selected = select.options[select.selectedIndex];
                label.textContent = selected ? selected.textContent : '';
                optionsList.querySelectorAll('.custom-select-option').forEach((optionButton) => {
                    const isSelected = optionButton.dataset.value === select.value;
                    optionButton.classList.toggle('is-selected', isSelected);
                    optionButton.setAttribute('aria-selected', isSelected ? 'true' : 'false');
                });
            };

            Array.prototype.slice.call(select.options).forEach((option) => {
                const optionButton = document.createElement('button');
                optionButton.type = 'button';
                optionButton.className = 'custom-select-option';
                optionButton.dataset.value = option.value;
                optionButton.textContent = option.textContent;
                optionButton.setAttribute('role', 'option');
                optionButton.addEventListener('click', () => {
                    select.value = option.value;
                    select.dispatchEvent(new Event('change', { bubbles: true }));
                    update();
                    wrapper.classList.remove('is-open');
                    button.setAttribute('aria-expanded', 'false');
                    button.focus();
                });
                optionsList.appendChild(optionButton);
            });

            button.addEventListener('click', () => {
                const willOpen = !wrapper.classList.contains('is-open');
                closeAllSelects(wrapper);
                wrapper.classList.toggle('is-open', willOpen);
                button.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
            });

            select.addEventListener('change', update);
            wrapper.append(button, optionsList);
            update();
        });

        document.addEventListener('click', (event) => {
            if (!event.target.closest('.select-field')) closeAllSelects();
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') closeAllSelects();
        });
    })();
</script>
</body>
</html>
