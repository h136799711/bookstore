alter table xiashu_book_page_content partition by range(book_id)
    (
    partition p0 values less than (10000),
     partition p1 values less than (20000),
    partition p2 values less than (30000),
    partition p3 values less than (40000),
     partition p4 values less than (50000),
     partition p5 values less than (60000),
     partition p6 values less than (70000),
     partition p7 values less than (80000),
     partition p8 values less than (90000),
     partition p9 values less than (100000),
     partition p10 values  less than (110000),
     partition p11 values less than (120000),
     partition p12 values less than (130000),
    partition p13 values less than (140000),
    partition p14 values less than (150000),
     partition p15 values less than (160000),
     partition p16 values less than (170000),
     partition p17 values less than (180000),
     partition p18 values less than (190000),
     partition p19 values less than MAXVALUE
	);
