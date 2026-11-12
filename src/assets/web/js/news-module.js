/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos-news
 * @author    Aria S.p.A. <protocollo@pec.ariaspa.it>
 * @license   GNU General Public License v3.0 <https://www.gnu.org/licenses/gpl-3.0.html>
 * @link      https://www.ariaspa.it/
 *
 * This file is part of OPEN 2.0.
 *
 * OPEN 2.0 is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OPEN 2.0 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OPEN 2.0. If not, see <https://www.gnu.org/licenses/>.
 */

$amos.newsModule = (function newsModule () {
    const newsModule = {};

    newsModule.init = function () {
        watch();
    };

    function watch () {
        $(document).on('initialized.owl.carousel, refreshed.owl.carousel', recalculateTop);
    }

    /* Per ogni gruppo di items sulla stessa cella da tre (solo su desktop quindi)
     * calcola, sulla base dell'item più alto, lo spazio disponibile sopra 
     * ogni .abstract e allinea tutti gli altri di conseguenza.
     */
    function recalculateTop () {
        const $items = $('#newsOwlCarousel .owl-item');

        $items.each(function () {
            $abstracts = $(this).find('.abstract');
            $abstracts.css('top', 'auto');

            var maxHeight = Math.max.apply(null, $abstracts.map(function () {
                return $(this).outerHeight();
            }).get());

            $abstracts.css('top', $(this).height() - maxHeight);
        });
    }

    return newsModule;
})();