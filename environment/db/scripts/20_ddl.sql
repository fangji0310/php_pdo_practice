use demo;
CREATE TABLE `sample` (
  `id` decimal(3,0) NOT NULL,
  `name` varchar(100) NOT NULL,
  `text` varchar(100),
  `register_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  `update_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`)
);
CREATE TABLE `expected_sample` (
  `id` decimal(3,0) NOT NULL,
  `name` varchar(100) NOT NULL,
  `text` varchar(100),
  `register_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  `update_datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`)
);
