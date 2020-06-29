create table log1
(
	date date not null,
	time time not null,
	ip inet not null,
	referer text not null,
	url text not null
);

create index log1_ip_index
	on log1 (ip);

create table log2
(
	ip inet not null,
	browser text not null,
	os text not null
);

create index log2_ip_index
	on log2 (ip);

