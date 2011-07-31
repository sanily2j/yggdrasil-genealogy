<?php

/***************************************************************************
 *   nb_canon.php                                                          *
 *   Yggdrasil: Norwegian "canonical" names                                *
 *                                                                         *
 *   Copyright (C) 2009-2011 by Leif B. Kristensen                         *
 *   leif@solumslekt.org                                                   *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU General Public License as published by  *
 *   the Free Software Foundation; either version 2 of the License, or     *
 *   (at your option) any later version.                                   *
 *                                                                         *
 *   This program is distributed in the hope that it will be useful,       *
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of        *
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         *
 *   GNU General Public License for more details.                          *
 *                                                                         *
 *   You should have received a copy of the GNU General Public License     *
 *   along with this program; if not, write to the                         *
 *   Free Software Foundation, Inc.,                                       *
 *   59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.             *
 ***************************************************************************/

// Split from source_search.php 2011-07-20

// NOTE: The following function and paragraph is a preliminary implementation.
// The regexp-replace terms are data rather than code, and should be moved into
// the database, see blog post http://solumslekt.org/blog/?p=151

function src_expand($s) {
    // regexp expansion of 'canonical' Norwegian names to catch variant forms.
    // NOTE: if eg. 'Tor' precedes 'Torsten', the 'Tor' part will be expanded,
    // and the string 'Torsten' is lost.
    $s = str_replace('Albert',    'Ah?lb[cdeghir]+th?', $s);
    $s = str_replace('Amborg',    'A[mn]+b[joø]+rg?', $s);
    $s = str_replace('Anders',    'An+[ader]+[sz]+', $s);
    $s = str_replace('Anne',      'An+[aeæ]+', $s);
    $s = str_replace('Alet',      'A[dehl]+i?[dts]+[eh]*', $s);
    $s = str_replace('Amund',     'Aa?[mn]+[ou]+nd?', $s);
    $s = str_replace('Arnold',    'Ah?re?n+h?[aou]+l+[dfhtvw]*', $s);
    $s = str_replace('Aslak',     'As[ch]*la[cghk]+', $s);
    $s = str_replace('Auen',      '(A|O)u?[dgvw]+[eu]+n?', $s);
    $s = str_replace('Berte',     'B[eiø]+r[gi]*t+h?[ae]*', $s);
    $s = str_replace('Bjørn',     'B[ij]+ø[ehr]*n', $s);
    $s = str_replace('Boel',      'Bod?[ei]+ld?e?', $s);
    $s = str_replace('Brynil',    'Br[ouyø]+n+[eijuy]+l[dvf]*', $s);
    $s = str_replace('Bærulf',    'B[eæ]+r[ou]+l[dfvw]*', $s);
    $s = str_replace('Børge',     'B[eijoø]+r+[aegij]+r?', $s);
    $s = str_replace('Carl',      '(C|K)arl+', $s);
    $s = str_replace('Catrine',   '(C|K)h?ath?a?rin[ae]+', $s);
    $s = str_replace('Claus',     '(C|K)la[eu]*s+', $s);
    $s = str_replace('Daniel',    'Dah?n+ie?l+d?', $s);
    $s = str_replace('David',     'Da[fuvw]+i[dth]+', $s);
    $s = str_replace('Dorte',     'D(aa|o)ro?[dt]+h?[aeij]+', $s);
    $s = str_replace('Eilert',    'E[hijy]*l+ert?h?', $s);
    $s = str_replace('Einar',     'E[hijy]*n+[ae]+r', $s);
    $s = str_replace('Elin',      'El+[eij]+n?', $s);
    $s = str_replace('Ellef',     'El+[ei]+[fvw]+', $s);
    $s = str_replace('Engebret',  '(I|E)(ng[el]+|m)b[ceghir]+th?', $s);
    $s = str_replace('Erik',      'Er[ei]+[chk]+', $s);
    $s = str_replace('Even',      'Ei?[fvw]+[eiu]+nd?', $s);
    $s = str_replace('Fredrik',   'Fr[ei]+dri[chk]+', $s);
    $s = str_replace('Gaute',     'G[ahou]+[dt]+h?[ei]+', $s);
    $s = str_replace('Gjermund',  '(G|J)[ehij]+rm[ou]+nd?', $s);
    $s = str_replace('Gjertrud',  '(G|J)[ehij]+rd?th?ru[de]*', $s);
    $s = str_replace('Gjert',     '(G|J)[eij]+rd?th?', $s);
    $s = str_replace('Gjest',     '(G|J)[eijou]+s+(th?|e)', $s);
    $s = str_replace('Gudmund',   'Gu+[dlmn]+und?', $s);
    $s = str_replace('Gullik',    'Gun?l+[ei]+[chk]+', $s);
    $s = str_replace('Gunder',    'G[ouø]+n+d?[ae]+r?', $s);
    $s = str_replace('Gunhild',   'G[ouø]+n+h?[ei]+l+d?[ae]*', $s);
    $s = str_replace('Halvor',    'H[ao]+l*[fuvw]+[aeo]+r+d?', $s);
    $s = str_replace('Henrik',    'Hend?r[ei]+[chk]+', $s);
    $s = str_replace('Håvald',    '[AHO]+[ao]*[vw]+[ao]+[lr]+d?', $s);
    $s = str_replace('Ingeborg',  '[EIJ]+e?ngeb[aijoø]r+[egh]*', $s);
    $s = str_replace('Isak',      'Isa+[chk]+', $s);
    $s = str_replace('Iver',      'I[fuvw]+[ae]+r', $s);
    $s = str_replace('Jon',       'Jo[eh]*n', $s);
    $s = str_replace('Johan',     'Jo[aeh]*n+[eis]*', $s);
    $s = str_replace('Kari',      'Kar[eijn]+', $s);
    $s = str_replace('Kirsti',    '(Ch?|K)[ij]+e?r?sth?[ein]+', $s);
    $s = str_replace('Kjell',     'K[ij]+el+d?', $s);
    $s = str_replace('Kjøstol',   '(Th?|K)[ijouyø]+r?st[aeou]+l+[dfhpvw]*', $s);
    $s = str_replace('Knut',      '(C|K)nu+[dt]+', $s);
    $s = str_replace('Lars',      'La[eu]*r[idt]*[sz]+', $s);
    $s = str_replace('Levor',     'Le+d?[vw]+[aeo]+r?d?', $s);
    $s = str_replace('Lisbet',    '(El|L)+i[sz]+a?[bp]+e[dht]+', $s);
    $s = str_replace('Lorens',    'L[ao]+[uvw]*r[ae]+n[tsz]+', $s);
    $s = str_replace('Mads',      'Ma[dht]*[aeiuæ]*[sz]+', $s);
    $s = str_replace('Malene',    'M[adeghir]+l+[ei]+n+[ae]*', $s);
    $s = str_replace('Margrete',  '(Gr?|Mar?g?)a?r?[ei]+t+h?[ae]*', $s);
    $s = str_replace('Mari',      'Mar[aeijn]+', $s);
    $s = str_replace('Mette',     'Met+h?e', $s);
    $s = str_replace('Mikkel',    'M[ei]+[chk]+[ae]+l+', $s);
    $s = str_replace('Mons',      'M[ao]+g?e?n+[dstz]+', $s);
    $s = str_replace('Nils',      'Nie?l+s', $s);
    $s = str_replace('Peder',     'P[det]+r', $s);
    $s = str_replace('Paul',      'P[aeouvw]+l+', $s);
    $s = str_replace('Rolf',      'R[oø]+l+[eouø]*[fvw]+', $s);
    $s = str_replace('Sissel',    '[CSZ]+[eiæ]+[dt]*[csz]+[ei]+l+[aei]*d?', $s);
    $s = str_replace('Siver',     'S[iy]+[gjvw]+[aeu]+[lr]+[dht]*', $s);
    $s = str_replace('Sofie',     'So[fhp]+[ij]+[aeæ]*', $s);
    $s = str_replace('Steffen',   'Ste[fhp]+[ae]+n', $s);
    $s = str_replace('Synnøve',   'S[eiouyø]+n+[aeiouyø]+[fhvw]*[ae]*', $s);
    $s = str_replace('Søren',     'S[eø]+[fvw]*e?r[ei]+n', $s);
    $s = str_replace('Tallak',    'Th?[ao]+l+a[chgk]+', $s);
    $s = str_replace('Tollef',    'Th?[eoø]+l+[eouø]+[vwf]+', $s);
    $s = str_replace('Tomas',     'Th?om+[ae]+s', $s);
    $s = str_replace('Torbjørn',  'Th?oe?rb[ij]+ør?n', $s);
    $s = str_replace('Torger',    'Th?or[egiju]+[rs]+', $s);
    $s = str_replace('Torkil',    'Th?[eoø]+r[chk]+[ie]+l+d?', $s);
    $s = str_replace('Tormod',    'Th?ormo[de]*', $s);
    $s = str_replace('Torsten',   'Th?(aa|o)r?ste+n', $s);
    $s = str_replace('Tor',       'Th?o[der]+', $s);
    $s = str_replace('Tov',       'Th?o[fuvw]+', $s);
    $s = str_replace('Trond',     'Th?roe?nd?', $s);
    $s = str_replace('Tyge',      'Th?[yø]+[chgk]+[ei]+r?', $s);
    $s = str_replace('Vrål',      '(V|W)r(aa|o)[eh]*l+d?', $s);
    $s = str_replace('Wilhelm',   '(V|W)[ei]+l+[ehlou]+m', $s);
    $s = str_replace('Zacharias', '(S|Z)a[chk]+[airs]+', $s);
    $s = str_replace('Åge',       '(Aa|O)[cghk]+[ei]+', $s);
    $s = str_replace('Åse',       '(Aa|O)st?e+', $s);
    $s = str_replace('Åshild',    '(Aa|O)r?s+h?[ei]+l+[de]*', $s);
    $s = str_replace('Åsold',     '(Aa|O)s+[eou]+l+[dfvw]*', $s);
    $s = str_replace('Åvet',      '(Aa?|O)[gvw]+[aeio]+[dht]+[ae]*', $s);
    return $s;
}

echo
    "<p>Regulære søkeuttrykk er definert for:<br />
    Albert, Alet, Amborg, Anders, Anne, Amund, Arnold, Aslak, Auen, Berte,
    Bjørn, Boel, Brynil, Bærulf, Børge, Carl, Catrine, Claus, Daniel, David,
    Dorte, Eilert, Einar, Elin, Ellef, Engebret, Erik, Even, Fredrik, Gaute,
    Gjermund, Gjert, Gjertrud, Gjest, Gudmund, Gullik, Gunder, Gunhild, Halvor,
    Henrik, Håvald, Ingeborg, Isak, Iver, Jon, Johan(nes), Kari, Kirsti, Kjell,
    Kjøstol, Knut, Lars, Levor, Lisbet, Lorens, Mads, Malene, Margrete, Mari,
    Mette, Mikkel, Mons, Nils, Peder, Paul, Rolf, Sissel, Siver, Sofie, Steffen,
    Synnøve, Søren, Tallak, Tollef, Tomas, Tor, Torbjørn, Torger, Torkil,
    Tormod, Torsten, Tov, Trond, Tyge, Vrål, Wilhelm, Zacharias, Åge, Åse,
    Åshild, Åsold, Åvet.</p>\n";

?>
