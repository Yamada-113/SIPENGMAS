<style>
    .action-card { transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out; border-color: #8BC3B4 !important; cursor: pointer; }
    .action-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(139, 195, 180, 0.5) !important; background-color: #f0f8ff; }
    .action-card:hover .action-icon { fill: #378a75; }
    .action-card:hover .action-title { color: #378a75 !important; }
    
    .stat-card { transition: all 0.2s ease-in-out; border: 1px solid #e9ecef; }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1) !important; border-color: #8BC3B4; cursor: pointer; }
    .stat-card .card-title { font-weight: 700; color: #343a40; }
    .stat-card svg { transition: transform 0.2s ease-in-out; }
    .stat-card:hover svg { transform: scale(1.15) rotate(-3deg); }
    
    .card-title { font-weight: 600; }
    .card-header { font-weight: 600; }
    
    .btn-download { background-color: transparent; border: 1px solid #8BC3B4; color: #8BC3B4; font-weight: 600; transition: all 0.2s ease-in-out; }
    .btn-download:hover { background-color: #8BC3B4; color: white; transform: translateY(-2px); box-shadow: 0 4px 8px rgba(139, 195, 180, 0.3); }
    
    .list-group-item { border-bottom: 1px solid rgba(0, 0, 0, 0.05) !important; }
    .list-group-item:last-child { border-bottom: 0 !important; }
</style>