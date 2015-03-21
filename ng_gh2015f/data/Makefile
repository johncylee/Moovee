JSONFILES=collision.json group_CATEGORY.json group_CTITLE.json \
group_DATE.json group_GRADE.json group_PLACE.json group_REMARK.json \
movies.json

.PHONY: all
all: gh2015f.json gh2015f.txt $(JSONFILES)

gh2015f.json: gh2015f.txt
	python moovee_parser.py $< > $@

gh2015f.txt:
	python moovee_scrap.py > $@

$(JSONFILES): gh2015f.txt
	php ../../includes/prepare.php $<

.PHONY: clean
clean:
	rm -f $(JSONFILES)
