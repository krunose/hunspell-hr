%include version.inc

Name:		hunspell-hr
Version:	%{PKG_VER}
Release:	%{PKG_BUILD}
Epoch:		1
Summary:	Croatian hunspell dictionaries

License:	GPL 2.0/LGPL 2.1/MPL 1.1
URL:		https://www.github.com/krunose/hunspell-hr
Source0:	%{name}-%{version}-%{release}.tar.gz

Requires:	hunspell
BuildArch:	noarch

%description
Croatian hunspell dictionaries.

%prep
echo PREP
%setup -q -n %{name}-%{version}-%{release}
%build
%install
rm -rf $RPM_BUILD_ROOT
mkdir -p $RPM_BUILD_ROOT/usr/share/myspell
cp -p hr_HR.aff hr_HR.dic $RPM_BUILD_ROOT/usr/share/myspell/

%files
%doc README_hr_HR.txt
%{_datadir}/myspell/*

%changelog
* Fri Feb 29 2019 Ante Smolcic %{version}
 - Hyphen discarded. Wordlist updated. Build script added.
