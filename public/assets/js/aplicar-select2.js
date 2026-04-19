/**
 * Inicializa Select2 em todo <select class="aplicar-select2">.
 *
 * Opcionais:
 *   data-select2-placeholder="Texto"     — placeholder (senão: texto da primeira <option value=""> ou "Selecione")
 *   data-select2-allow-clear="true|false" — padrão: true se existir <option value="">
 *   data-select2-refresh-on="#idOuSeletor" — em change desse elemento, recria o Select2 (ex.: UF ao trocar cidades)
 *
 * Chame initAplicarSelect2(escopo) depois de preencher options via JS (ex.: cidades/estados).
 */
(function (factory) {
    if (typeof window.jQuery !== 'undefined') {
        factory(window.jQuery);
    }
})(function ($) {
    'use strict';

    function select2OptionsFromElement($el) {
        var placeholder =
            $el.attr('data-select2-placeholder') ||
            $el.find('option[value=""]').first().text() ||
            'Selecione';

        var allowClearAttr = $el.attr('data-select2-allow-clear');
        var allowClear;
        if (allowClearAttr === undefined || allowClearAttr === '') {
            allowClear = $el.find('option[value=""]').length > 0;
        } else {
            allowClear = allowClearAttr === 'true' || allowClearAttr === '1';
        }

        var opts = {
            width: '100%',
            placeholder: placeholder,
            allowClear: !!allowClear
        };

        var dropdownParent = $el.attr('data-select2-dropdown-parent');
        if (dropdownParent) {
            var $dp = $(dropdownParent);
            if ($dp.length) {
                opts.dropdownParent = $dp;
            }
        }

        return opts;
    }

    function bindRefreshOn($select) {
        var refreshSel = ($select.attr('data-select2-refresh-on') || '').trim();
        if (!refreshSel) {
            return;
        }
        var id = $select.attr('id');
        if (!id) {
            return;
        }
        var ns = '.aplicarS2_' + id.replace(/[^a-zA-Z0-9_]/g, '_');
        var $triggers = $(refreshSel);
        if (!$triggers.length) {
            return;
        }

        $triggers.off('change' + ns).on('change' + ns, function () {
            setTimeout(function () {
                if ($select.data('select2')) {
                    $select.select2('destroy');
                }
                $select.select2(select2OptionsFromElement($select));
            }, 0);
        });
    }

    /**
     * @param {Element|string|JQuery} [root] — escopo (default: document)
     */
    window.initAplicarSelect2 = function (root) {
        if (!$.fn.select2) {
            return;
        }
        var $root = root ? $(root) : $(document);
        $root.find('select.aplicar-select2').each(function () {
            var $s = $(this);
            if ($s.data('select2')) {
                $s.select2('destroy');
            }
            $s.select2(select2OptionsFromElement($s));
            bindRefreshOn($s);
        });
    };
});
