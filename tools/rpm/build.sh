#!/bin/bash
if [ "$1" == "" ] || [ "$2" == "" ]
then
	echo "Parametri: build <version> <build>"
	exit
fi

PKG_NAME=hunspell-hr
PKG_VER=$1
PKG_BUILD=$2

RPM_DIR=~/rpmbuild
RPM_SOURCES_DIR=$RPM_DIR/SOURCES

RELEASE_VER=$PKG_VER-$PKG_BUILD
RELEASE_URL=https://github.com/krunose/hunspell-hr/archive/$RELEASE_VER.tar.gz

FILE_PACKAGE_SPEC=$PKG_NAME.spec
FILE_PACKAGE_SOURCE=$RPM_SOURCES_DIR/$PKG_NAME-$PKG_VER-$PKG_BUILD.tar.gz

echo [wget] Download release from $RELEASE_URL...
wget -nv --output-document=$FILE_PACKAGE_SOURCE $RELEASE_URL
if [ $? -ne 0 ]
then
    exit #?
fi

echo [rpmbuild] Creating version macros...
VERSION_MACRO_FILE=version.inc
echo "%global PKG_VER $PKG_VER" > $VERSION_MACRO_FILE
echo "%global PKG_BUILD $PKG_BUILD" >> $VERSION_MACRO_FILE

echo [rpmbuild] Building RPMs...
rpmbuild --quiet -bb $FILE_PACKAGE_SPEC

echo DONE. Listing built RMPs:
ls -l $RPM_DIR/RPMS/noarch
