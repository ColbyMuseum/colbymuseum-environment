<?php get_header(); ?>

<style>
table tr th {
	text-align: right;
}
</style>

<?php while ( have_posts() ) : the_post(); ?>
	<h1><?php echo $post->post_title ?></h2>

	<p><a href="/its/adit-projects/">ADIT Projects</a></p>

	<table>
		<tr>
			<th>Project Type</th>
			<td><?php echo get_field('project_type'); ?></td>
		</tr>
		<tr>
			<th>Department</th>
			<td><?php echo get_field('department'); ?></td>
		</tr>
		<tr>
			<th>Requested By</th>
			<td><?php echo get_field('requested_by'); ?></td>
		</tr>
		<tr>
			<th>Description</th>
			<td><?php echo get_field('description'); ?></td>
		</tr>
		<tr>
			<th>Primary Contact</th>
			<td><?php echo get_field('primary_contact'); ?></td>
		</tr>
		<tr>
			<th>Status</th>
			<td><?php echo get_field('status'); ?></td>
		</tr>
		<tr>
			<th>Dependancies</th>
			<td><?php echo get_field('dependancies'); ?></td>
		</tr>
		<tr>
			<th>Requested Completion Date</th>
			<td><?php echo get_field('requested_completion_date'); ?></td>
		</tr>
		<tr>
			<th>Assigned To</th>
			<td><?php echo get_field('assigned_to'); ?></td>
		</tr>
		<tr>
			<th>Notes</th>
			<td><?php echo get_field('notes'); ?></td>
		</tr>
		<tr>
			<th>Field 1</th>
			<td><?php echo get_field('field1'); ?></td>
		</tr>
		<tr>
			<th>Field 2</th>
			<td><?php echo get_field('field2'); ?></td>
		</tr>
	</table>
<?php endwhile; ?>

</php get_footer(); ?>
