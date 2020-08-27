*WARNING: This repository is now archived, it is superseded by the [outils.biblissima.fr](https://github.com/biblissima/outils.biblissima.fr) repository, where you will find the files and instructions to run your own instance of Eulexis locally.*

# Eulexis

Eulexis is a web lemmatiser for Ancient Greek texts. It allows to search for a word in ancient greek dictionaries (Liddel-Scott-Jones, Pape, Bailly), to decline a root word and lemmatise a text.

It is developed by Philippe Verkerk (@PhVerkerk) with the help of Régis Robineau as part of the [Biblissima Toolkit](http://outils.biblissima.fr).

Eulexis on Biblissima website : [http://outils.biblissima.fr/eulexis](http://outils.biblissima.fr/eulexis)

## Install

In order to run your own instance of Eulexis locally, you must download the following assets:
- data files used by `eulexis.php`: [http://outils.biblissima.fr/resources/eulexis/data.tar.gz](http://outils.biblissima.fr/resources/eulexis/data.tar.gz)
- a sample user interface to able to use the web application: [http://outils.biblissima.fr/resources/eulexis/ui.tar.gz](http://outils.biblissima.fr/resources/eulexis/ui.tar.gz)

You should extract these two archives at the root of your `eulexis` folder (`eulexis/data` and `eulexis/ui`).

## License

This program is made available by Philippe Verkerk under the [Creative Commons Attribution-NonCommercial 4.0 International License](http://creativecommons.org/licenses/by-nc/4.0/) (CC BY-NC).

## Credits

Un grand merci à Philipp Roelli, André Charbonnet, Peter J. Heslin, Yves Ouvrard, Eduard Frunzeanu et Régis Robineau.

* Le LSJ est de [Philipp Roelli](http://www.mlat.uzh.ch/MLS/), revu et corrigé par [Chaeréphon](http://chaerephon.e-monsite.com/medias/files/bailly.html) (André Charbonnet)
* Le Pape est de [Philipp Roelli](http://www.mlat.uzh.ch/MLS/)
* L'abrégé du Bailly est de [Chaeréphon](http://chaerephon.e-monsite.com/medias/files/bailly.html)
* La lemmatisation et la flexion ont été possibles grâce aux fichiers de [Diogenes](https://community.dur.ac.uk/p.j.heslin/Software/Diogenes) et de [Perseus](http://www.perseus.tufts.edu/)
