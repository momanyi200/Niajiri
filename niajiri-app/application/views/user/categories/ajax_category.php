
			<?php
			if(!empty($category)) {
				foreach ($category as $crows) {
					$category_name=strtolower($crows['category_name']);
					$category_slug=strtolower($crows['category_slug']);
				?>
			<div class="col-lg-4 col-md-6">
				<a href="<?php echo base_url();?>search/<?php echo str_replace(' ', '-', $category_slug);?>">
					<div class="cate-widget">
						<?php if ($crows['category_image'] != '') { ?>
							<img src="<?php echo base_url().$crows['category_image'];?>" alt="">
					    <?php } else { ?>
                            <img src="<?php echo base_url(); ?>assets/img/service-placeholder.jpg">
                        <?php } ?>
						<div class="cate-title">
							<h3><span><i class="fas fa-circle"></i> <?php echo ucfirst($crows['category_name']);?></span></h3>
						</div>
						<div class="cate-count">
							<i class="fas fa-clone"></i> <?php echo $crows['category_count'];?>
						</div>
					</div>
				</a>
			</div>
			<?php } }
			else { 

			echo '<div class="col-lg-12">
			<div class="category">
			No Categories Found
			</div>
			</div>';
			} 

			echo $this->ajax_pagination->create_links();
			?>
			<script src="<?php echo base_url();?>assets/js/functions.js"></script>	