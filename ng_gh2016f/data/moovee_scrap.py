# -*- coding: utf-8 -*-
#
# Copyright 2015, John Lee <john.cylee@gmail.com>
#
# Licensed under the Apache License, Version 2.0 (the "License");
# you may not use this file except in compliance with the License.
# You may obtain a copy of the License at
#
#     http://www.apache.org/licenses/LICENSE-2.0
#
# Unless required by applicable law or agreed to in writing, software
# distributed under the License is distributed on an "AS IS" BASIS,
# WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
# See the License for the specific language governing permissions and
# limitations under the License.

import csv
import sys
import urllib
import urlparse

from bs4 import BeautifulSoup


def strip(c):
    if c in ('\r', '\t', '\n'):
        return False
    return True


def parse_movie(url, movie):
    f = urllib.urlopen(url)
    bs = BeautifulSoup(f)
    movie['CATEGORY'] = bs.find('td', class_='type').text
    titles = bs.find('td', class_='pro_title').contents
    movie['CTITLE'] = filter(strip, titles[0]).strip()
    movie['ETITLE'] = filter(strip, titles[2]).strip()
    return movie


def parse_schedule(url, writer):
    f = urllib.urlopen(url)
    bs = BeautifulSoup(f)
    tables = bs.body.find_all('table', class_='schedule_table')
    date = tables[0].previous_sibling.previous_sibling.text
    for table in tables:
        place = table.find('td', class_='schedule_table_place').text
        rows = table.find_all('tr')[2:]
        for row in rows:
            movie = {'DATE': date, 'PLACE': place}
            columns = row.find_all('td')
            movie['TIME'] = columns[0].text
            link = urlparse.urljoin(url, columns[1].a['href'])
            movie['DURATION'] = columns[2].text.replace('min', '')
            movie['GRADE'] = columns[3].text
            movie['REMARK'] = columns[4].text
            movie['PAGE'] = 'NA'
            movie = parse_movie(urlparse.urljoin(url, link), movie)
            writer.writerow(movie)


class UTF8DictWriter:

    def __init__(self, *args, **kwds):
        self.writer = csv.DictWriter(*args, **kwds)

    def writerow(self, row):
        output = dict(row)
        for k, v in row.iteritems():
            if type(v) == unicode:
                output[k] = v.encode('utf-8')
        self.writer.writerow(output)

    def writeheader(self):
        self.writer.writeheader()


def main():
    urls = ['http://www.ghfff.org.tw/program/schedule.aspx?date=2016/04/%02d' %
            d for d in xrange(8, 18)]
    fields = ('PLACE', 'DATE', 'TIME', 'DURATION', 'CATEGORY', 'CTITLE',
              'ETITLE', 'GRADE', 'REMARK', 'PAGE')
    writer = UTF8DictWriter(sys.stdout, fields, dialect='excel-tab')
    writer.writeheader()
    for url in urls:
        parse_schedule(url, writer)


if __name__ == '__main__':
    main()
