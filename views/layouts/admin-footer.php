  </div><!-- /page-content -->
</div><!-- /main -->
</div><!-- /admin-wrap -->

<!-- Overlay for mobile sidebar -->
<div id="sidebar-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:199"
  onclick="document.getElementById('sidebar').classList.remove('open');this.style.display='none'"></div>

<script>
// Mobile sidebar toggle
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('sidebar-overlay');
document.querySelector('.sidebar-toggle')?.addEventListener('click', function(){
  const isOpen = sidebar.classList.toggle('open');
  overlay.style.display = isOpen ? 'block' : 'none';
});

// Auto-close alert after 5s
document.querySelectorAll('.alert').forEach(function(el){
  setTimeout(function(){ el.style.opacity='0'; el.style.transition='opacity .4s'; setTimeout(()=>el.remove(),400); }, 5000);
});
</script>
</body>
</html>
