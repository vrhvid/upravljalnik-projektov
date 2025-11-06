# Aplikacija za upravljanje projektov
Verzija: 0.1.0
Datum: 6. 11. 2025

## Zahteve
- PHP knjižnica MongoDB
- PHP razširitev MongoDB

## Uporaba
1. iz repozitorija PECL prenesi ustrezno verzijo PHP razširitve MongoDB
2. iz repozitorija Composer prenesi ustrezno verzijo PHP knjižnice MongoDB
3. ustvari MongoDB bazo podatkov, ki mora vsebovati:
   - zbirko _users_ z dokumentom s podatki \[name(String): \[ime administratorskega uporabnika po izbiri\], surname(String): \[priimek administratorskega uporabnika po izbiri\], email(String): \[email po izbiri\], password(String): \[geslo po izbiri\], roles(Array): {1}\]
   - zbirko _status_ z dokumenti:
     - \[numericId(Int32): 1, status(String): "Aktiven\]
     - \[numericId(Int32): 2, status(String): "Na čakanju\]
     - \[numericId(Int32): 3, status(String): "Ideja\]
     - \[numericId(Int32): 4, status(String): "Arhiviran\]
   - zbirko _tasks_
4. prenesi datoteke iz repozitorija na strežnik
5. na strežniku nastavi, da strežnik ob zahtevani mapi s projektom vrne datoteko _login.html_
6. v spletno mesto se prijavi z administratorskim računom, preko katerega ustvari uporabniški račun

## Spremembe
- _v0.1.0_ (6. 11. 2025):
  - prva postavitev projekta
