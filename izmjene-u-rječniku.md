# Izmjene u rječniku


Najnovija se inačica rječnika može preuzeti iz grane `master` repozitorija na adresi [github.com/krunose/hr-hunspell](https://github.com/krunose/hr-hunspell).




### Inačica 2.1.1

- ispravak zatipka: iglun/205i > iglun/205 ([nedostatak #2](https://github.com/krunose/hr-hunspell/issues/2))
- proširen REP sa četrnaest novih unosa
- dodano četrnaest novih riječi
- povezani nastavci (hr_HR.aff) s nekoliko riječi (hr_HR.dic)
- ispravak velikoga slova u nekoliko riječi


## Inačica 2.1


- izmijenjen ili dodan veći broj riječi
- proširen i sortiran REP do najduljih unosa do najkraćih [&#42;]
- proširen BREAK (LO Bugzilla #106989)
- uklonjene kratice s točkom (v. problem br. 231 na [github.com/husnpell/issues](https://github.com/hunspell/hunspell/issues))
- bolji opis projekta (datoteka `README.md`)

[&#42;] Važno je razvrstati unose u dijelu REP tako da najdulji unosi budu na vrhu, a najkraći na dnu. Ne mogu potvrditi (teško je pronaći savršen primjer), ali izgleda da Hunspell primjenjuje REP po redu, od vrha te uzima u obzir samo prvu zamjenu koja rezultira pravopisno točnom riječju. Odnosno, ako se na vrh popisa stavi zamjena s kratkim uvjetom koji je primjenjiv na veći broj riječi a da zamjena daje pravopisno točnu riječ, Hunspell neće ni pokušavati primijeniti zamjene koje slijede. Temeljem iskustva i osjećaja može se zaključiti kako predlaganje bolje funkcionira ako su uvjeti dulji (konkretniji) i poredani od najduljega prema najkraćemu jer se time smanjuje mogućnost primjene uvjeta na neodgovarajuću pravopisnu pogrešku. Treba izbjegavati unose poput 'REP c ds' (gracki > gradski) te širiti takve uvjete što je više moguće: 'REP rack radsk' kako bi se smanjila mogućnost primjenjivanja uvjeta 'c' na (pre)velik broj riječi. Primjer nije savršen, ali ilustrira čemu treba težiti prilikom pisanja ovih pravila. Broj je riječi koje zadovoljavaju uvjet 'c' 6447 (travanj 2017.), a broj je riječi koji zadovoljava uvjet'rack' (starogracki > starogradski) svega trinaest čime se uvelike smanjuje mogućnost pojave lažno pozitivnih rezultata. Tako 'c ds' treba zamijeniti s nekoliko unosa: 'REP rack radsk' (starogracki > starogradsk), 'REP uctv udstv' (suctvo > sudstvo), 'REP recje redsje' (precjednik > predsjednik) itd. Potrebno je napraviti analizu te ustanoviti kada se više takvih unosa mogu svesti pod jedan radi ekonomičnosti, ali je vrlo važno ograničiti ispunjenje uvjeta samo na željene riječi te je zbog toga najkraće uvjete potrebno staviti na dno jer su potencijalno (pogrešno) primjenjivi na (pre)velik broj riječi. Prvo je potrebno pokušati sa specifičnim, a tek onda općenitim.

Ni ovakav pristup ne rješava problem u potpunosti pa je važno smanjiti mogućnost primjenjivanja uvjeta na velik broj riječi, a razvrstavanje od najduljega uvjeta prema najkraćemu u tome pomaže. Isključivanje zastarjelih i stilski obilježenih riječi koje se rijetko koriste isto može pomoći. Druga je mogućnost držanje dva rječnika, uži u kojega se takve riječi ne bi unosile te širi u koji bi se unosile. Smanjen broj riječi u prvome opravdao bi se manjim brojem dvojbenih situacija.

Inačica 2.1.1 primjer je širega rječnika.

---

## Inačica 2.0

veljača 2017.

Strukturna revizija i kompletna izmjene inačice 1.0.

- definiranje novih pravila tvorbe riječi u hr_HR.aff datoteci.
- znatno reduciranje ukupnog broja riječi u hr_HR.dic isključivanjem riječi izvedenih novim pravilima tvorbe iz temeljne riječi.
- proširenje rječnika dodavanjem novih riječi.
- inicijalno testiranje i analiza skupova riječi, usuglašavanje razlika u odnosu na inačicu 1.1

Velika hvala Mirku Kosu koji je pripremio ovu inačicu.


---

## Inačica 1.1

god. 2014.

Nadopuna i nadogradnja inačice 0.1

- dodana kompresija nastavaka (engl. alias compression)
- pretvorba iz ISO8859-2 u UTF-8
- dodane nove riječi
- uklonjena pogreške uzrokovane računalnom obradom liste riječi u inačici 1.0

---

## Inačica 1.0

god. 2003.

Originalni rječnik - autor D. L. iz 2003. g. [&#42;]

---

[&#42;] http://cvs.linux.hr/spell/
