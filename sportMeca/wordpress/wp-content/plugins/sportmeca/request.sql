--Donne tous les items commandés et leur quantité, pour chaque acheteur
select * from
(
	select wp_woocommerce_order_items.order_item_id as idt1, wp_postmeta.meta_value as "buyer_id", wp_woocommerce_order_itemmeta.meta_value as "qty"  from wp_posts 
	inner join wp_woocommerce_order_items on wp_posts.ID = wp_woocommerce_order_items.order_id 
	left join wp_postmeta on wp_postmeta.post_id = wp_posts.ID
	left join wp_woocommerce_order_itemmeta on wp_woocommerce_order_itemmeta.order_item_id = wp_woocommerce_order_items.order_item_id
	where wp_postmeta.meta_key = '_customer_user' and wp_woocommerce_order_itemmeta.meta_key = '_qty'
) as t1
inner join
(
	select wp_woocommerce_order_items.order_item_id as idt2, wp_woocommerce_order_itemmeta.meta_value as "product_id"  from wp_posts 
	inner join wp_woocommerce_order_items on wp_posts.ID = wp_woocommerce_order_items.order_id 
	left join wp_postmeta on wp_postmeta.post_id = wp_posts.ID
	left join wp_woocommerce_order_itemmeta on wp_woocommerce_order_itemmeta.order_item_id = wp_woocommerce_order_items.order_item_id
	where wp_postmeta.meta_key = '_customer_user' and wp_woocommerce_order_itemmeta.meta_key = '_product_id' and wp_woocommerce_order_items.order_id = 258
) as t2
on t1.idt1 = t2.idt2


--Donne tous les achats d'un acheteur
select wp_woocommerce_order_items.order_item_id as idt1, wp_postmeta.meta_value as "id acheteur", wp_woocommerce_order_itemmeta.meta_value as "quantité"  from wp_posts 
inner join wp_woocommerce_order_items on wp_posts.ID = wp_woocommerce_order_items.order_id 
left join wp_postmeta on wp_postmeta.post_id = wp_posts.ID
left join wp_woocommerce_order_itemmeta on wp_woocommerce_order_itemmeta.order_item_id = wp_woocommerce_order_items.order_item_id
where wp_postmeta.meta_key = '_customer_user' and wp_woocommerce_order_itemmeta.meta_key = '_qty'

--Donne le contenu d'une commande, avec le détail sur les produits (quantité, catégorie)
select buyer_id, product_id, qty, post_name from wp_posts
inner join
(
	select wp_woocommerce_order_items.order_item_id as idt2, wp_woocommerce_order_itemmeta.meta_value as 'product_id' from wp_posts 
	inner join wp_woocommerce_order_items on wp_posts.ID = wp_woocommerce_order_items.order_id 
	left join wp_postmeta on wp_postmeta.post_id = wp_posts.ID
	left join wp_woocommerce_order_itemmeta on wp_woocommerce_order_itemmeta.order_item_id = wp_woocommerce_order_items.order_item_id
	where wp_postmeta.meta_key = '_customer_user' and wp_woocommerce_order_itemmeta.meta_key = '_product_id' and wp_woocommerce_order_items.order_id = 258
) as t2
on wp_posts.id = product_id
inner join
(
	select wp_woocommerce_order_items.order_item_id as idt1, wp_postmeta.meta_value as "buyer_id", wp_woocommerce_order_itemmeta.meta_value as "qty"  from wp_posts 
	inner join wp_woocommerce_order_items on wp_posts.ID = wp_woocommerce_order_items.order_id 
	left join wp_postmeta on wp_postmeta.post_id = wp_posts.ID
	left join wp_woocommerce_order_itemmeta on wp_woocommerce_order_itemmeta.order_item_id = wp_woocommerce_order_items.order_item_id
	where wp_postmeta.meta_key = '_customer_user' and wp_woocommerce_order_itemmeta.meta_key = '_qty'
) as t1
on t1.idt1 = t2.idt2

--Donne les produits
select post_name as type, post_excerpt as race_name from wp_posts
where post_type = 'product'

--Donne le numéro de course, pour une clé équipage
select max(race_number) from wp_pilotes 
where team_key = '025176372016187WednesdayJuly14678259001categorie=duo-motoid=1product=262'
and race_number != 0
and race_name = ''

--Le plus grand numéro de la catégorie
