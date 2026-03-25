# iocexportl: guia de flux d’exportació LaTeX/PDF

Aquest document resumeix l’arquitectura funcional del plugin `iocexportl`, amb especial focus en el flux de taules i en el paper de `generate_latex.php`.

## 1) Visió general ràpida

L’exportació a PDF es divideix en tres responsabilitats:

- `syntax/ioctable.php`: interpreta la directiva wiki de taules (`::table:...`) i activa flags de sessió.
- `renderer/iocexportl.php`: transforma instruccions wiki en codi LaTeX concret (entorns, captions, columnes, etc.).
- `generate_latex.php`: orquestra tot el procés de generació (configuració, plantilles, render global, compilació i retorn de fitxers).

## 2) Flux complet d’execució

1. L’usuari demana exportació (PDF o ZIP).
2. `generate_latex.php::init()` valida permisos i paràmetres.
3. Detecta la versió de LaTeX i resol el backend de taules (`auto/new/legacy`).
4. Carrega `templates/header.ltx` i aplica adaptacions si backend = `new`.
5. Desa en sessió el backend resolt (`iocexportl_table_backend`) i la versió de LaTeX.
6. Obté dades de portada/metadades i pàgines a exportar.
7. Per cada pàgina: `get_latex_instructions(...)` + `p_latex_render('iocexportl', ...)`.
8. El renderer genera el LaTeX final de contingut (incloses taules).
9. S’afegeix `footer.ltx`.
10. `createLatex()` compila amb `pdflatex` (passades draft + passada final).
11. `returnData()` publica resultat (PDF/ZIP o LOG d’error).

## 3) Funció ampliada de generate_latex.php

`generate_latex.php` no és “només compilació”; és el **coordinador central**.

### 3.1 Orquestració i control d’entrada

- Controla mode d’exportació (`pdf` o `zip`).
- Valida permisos d’usuari i política d’exportació.
- Decideix si cal retornar dades estructurades o resposta directa.

Això evita que renderer i sintaxi hagin de gestionar seguretat o permisos.

### 3.2 Resolució de backend de taules

Inclou una política explícita:

- `new`: força stack nou de taules.
- `legacy`: força stack antic.
- `auto`: decideix segons versió LaTeX.

Mètodes clau:

- `resolveTableBackend($latex_version)`
- `supportsNewTableBackend($latex_version)`

Aquest resultat es guarda a sessió (`$_SESSION['iocexportl_table_backend']`) perquè el renderer pugui prendre decisions coherents durant tota la generació.

### 3.3 Preparació de plantilla (header/footer)

- Llegeix `header.ltx`.
- Si backend = `new`, aplica substitucions de compatibilitat de paquets/macros.
- Renderitza portada/frontpage segons metadades i context (FPD, U0, doble cicle, etc.).
- Afegeix `footer.ltx` al final.

Així es concentra en un sol punt la variabilitat de presentació global.

### 3.4 Construcció del document (render de pàgines)

- Itera pàgines i activitats seleccionades.
- Cada pàgina passa per parser d’instruccions i renderer LaTeX.
- Manté estat de sessió necessari per comportaments de render (capítols, activitats, taules, QR, etc.).

### 3.5 Compilació robusta i diagnòstic

- Desa `.tex` a directori temporal.
- Executa diverses passades de `pdflatex` (necessàries per referències/taules).
- En error, prepara i retorna log (retallat o complet segons context).
- En èxit, retorna metadades útils (mida, pàgines, temps, ruta de media).

Això desacobla la lògica de negoci (què exportar) de la mecànica de compilació (com compilar).

## 4) Taules: com cooperen syntax, renderer i generator

### 4.1 syntax/ioctable.php

- Interpreta opcions `large/small/vertical/accounting/widths/type`.
- Activa flags de sessió (`table_small`, `table_large`, etc.).
- Gestiona wrappers contextuals d’entrada/sortida.

### 4.2 renderer/iocexportl.php

- Decideix entorns LaTeX concrets via `is_new_latex()` (basat en sessió/backend).
- Exemple `small`:
  - backend `new`: usa `table + tabularx`.
  - backend `legacy`: manté `SCtable/tabu`.

### 4.3 generate_latex.php

- Garanteix que la decisió de backend és única i estable durant tota l’exportació.
- Evita barrejar estratègies incompatibles dins del mateix document.

## 5) Canvis aplicats en aquesta incidència

1. Selecció de backend configurable (`auto/new/legacy`) amb resolució centralitzada.
2. Propagació de backend a sessió per al renderer.
3. Ajust del flux `:small:`:
   - `new`: caption dins d’un float `table` vàlid i cos en `tabularx`.
   - `legacy`: manteniment de comportament anterior amb `SCtable/tabu`.
4. Condicionalització de wrappers `SCtable` perquè no interfereixin en backend `new`.

## 6) Guia de manteniment (on tocar)

- Problemes de parseig d’opcions de taula: `syntax/ioctable.php`.
- Problemes de LaTeX generat per taules/captions/entorns: `renderer/iocexportl.php`.
- Problemes de política global d’exportació, versions, compilació o templates: `generate_latex.php`.

## 7) Nota pràctica de depuració

Quan una exportació falla:

1. Comprovar primer el backend efectiu (`new`/`legacy`) i el tipus de taula (`small/large`).
2. Revisar el primer error real del `.log` (`! ...`).
3. Verificar que els entorns obren/tanquen en el mateix backend.
4. Confirmar que `caption` està en context LaTeX vàlid (float si cal).

Aquesta seqüència redueix molt el temps de diagnòstic en incidències de taules.

## 8) Glossari ràpid

- **Backend de taules**: estratègia interna del plugin per generar taules (`new` o `legacy`). No és la instal·lació de LaTeX del sistema.
- **LaTeX del sistema**: versió real de `pdflatex`/TeX Live instal·lada al servidor. En mode `auto`, el plugin la fa servir per decidir backend.
- **Sessió (`$_SESSION`)**: espai d’estat compartit durant l’exportació, on es guarden flags i decisions (p. ex. `iocexportl_table_backend`).
- **Wrapper de sintaxi**: envoltori que obre/tanca context (entorns) abans/després del contingut d’una taula.
- **Renderer**: capa que escriu el LaTeX final de contingut (files, columnes, celes, captions, etc.) segons el backend actiu.
- **Generator (`generate_latex.php`)**: coordinador del procés complet (configuració, plantilla, render global, compilació i retorn de resultat).

## 9) Canvi de renderització de fórmules (`$$...$$`)

### 9.1 Problema detectat

Les fórmules en bloc delimitades amb `$$...$$` s’estaven convertint a mode matemàtic inline:

- Abans: `\begin{center}\begin{math} ... \end{math}\end{center}`

Això podia fallar o donar resultats inconsistents amb comandes pròpies de display math (p. ex. `\cfrac`).

### 9.2 Solució aplicada

S’ha canviat el renderitzat de bloc matemàtic perquè `$$...$$` es transformi en mode display real:

- Ara: `\[ ... \]`

Aquest canvi és més coherent amb l’ús estàndard de LaTeX per fórmules de bloc.

### 9.3 Fitxers modificats (fórmules)

- `syntax/ioclatex.php`
- `renderer/iocexportl.php`

La modificació s’ha aplicat tant a `dokuwiki_30` com a `wiki18`.

### 9.4 Què no s’ha tocat

- El comportament de fórmules inline `$...$` es manté igual.
- El comportament de `<latex>...</latex>` es manté igual.
- No s’han canviat paquets de plantilla per aquest ajust (ja hi havia `amsmath`).

### 9.5 Cas de validació

Exemple validat després del canvi:

`$$TE=\cfrac{O+4 \cdot M+P}{6}$$`

També validat un cas més llarg amb text i unitats (`\textit`, `\mathrm`, `\;`).
