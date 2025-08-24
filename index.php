<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Residency Issue Tracker</title>
  <meta name="description" content="A clean, responsive HTML + CSS-only Residency Issue Tracker UI" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root{
      --bg: #0b1020;
      --card: #121833;
      --card-2: #0f1530;
      --muted: #9aa4bf;
      --text: #e8ecf8;
      --primary: #6aa3ff;
      --primary-600: #3b82f6;
      --accent: #22d3ee;
      --success: #22c55e;
      --warning: #f59e0b;
      --danger: #ef4444;
      --ring: rgba(88, 134, 255, .35);
      --border: #243056;
      --badge-bg: rgba(255,255,255,.06);
      --shadow: 0 10px 25px rgba(0,0,0,.35), 0 3px 8px rgba(0,0,0,.25);
      --radius-xl: 18px;
      --radius-lg: 14px;
      --radius: 12px;
    }

    * { box-sizing: border-box; }
    html, body { height: 100%; }
    body{
      margin:0;
      font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, "Apple Color Emoji","Segoe UI Emoji";
      background: radial-gradient(1200px 1200px at 10% -10%, #142352 0%, rgba(10, 14, 31, 0) 60%),
                  radial-gradient(900px 900px at 110% 10%, rgba(63, 131, 248, 0.18) 0%, rgba(10, 14, 31, 0) 55%),
                  var(--bg);
      color: var(--text);
      line-height: 1.45;
    }

    /* Layout */
    .container{
      max-width: 1200px;
      margin: 0 auto;
      padding: 24px;
    }

    header{
      position: sticky;
      top: 0;
      backdrop-filter: blur(8px);
      background: linear-gradient(180deg, rgba(11,16,32,.85), rgba(11,16,32,.55));
      border-bottom: 1px solid var(--border);
      z-index: 50;
    }

    .nav{
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      padding: 14px 24px;
    }

    .brand{
      display:flex;align-items:center;gap:12px;font-weight:700;letter-spacing:.3px
    }
    .logo{width:34px;height:34px;border-radius:12px;background:linear-gradient(135deg,var(--primary),var(--accent));box-shadow:0 6px 18px rgba(58,120,255,.35)}
    .brand span{opacity:.9}

    .nav-actions{
      display:flex; gap:10px; align-items:center;
    }

    .btn{
      appearance: none; border: 1px solid var(--border); background: var(--card);
      color: var(--text); padding: 10px 14px; border-radius: 12px; cursor: pointer;
      transition: transform .05s ease, background .2s ease, border-color .2s ease;
      font-weight: 600; box-shadow: inset 0 0 0 1px rgba(255,255,255,.02);
    }
    .btn:hover{ transform: translateY(-1px); border-color: #2f3b67 }
    .btn.primary{ background: linear-gradient(180deg, var(--primary) 0%, var(--primary-600) 100%); border-color: transparent; color: #061227 }

    /* Page header */
    .page-head{
      display:grid; grid-template-columns: 1.4fr .8fr; gap:18px; align-items:stretch;
      margin-top: 18px;
    }
    @media (max-width: 900px){
      .page-head{ grid-template-columns: 1fr; }
    }

    .card{
      background: linear-gradient(180deg, rgba(255,255,255,.03), rgba(255,255,255,.01)), var(--card);
      border:1px solid var(--border);
      border-radius: var(--radius-xl);
      box-shadow: var(--shadow);
      padding: 18px;
    }

    .page-title{ display:flex; align-items:center; justify-content:space-between; gap:16px; }
    h1{ font-size: clamp(22px, 2.4vw, 30px); margin: 0 0 8px; }
    p.lead{ color: var(--muted); margin: 0; }

    .stats{
      display:grid; grid-template-columns: repeat(4, 1fr); gap:12px; margin-top:14px;
    }
    @media (max-width:700px){ .stats{ grid-template-columns: repeat(2, 1fr);} }

    .stat{ background: var(--card-2); border:1px solid var(--border); border-radius:14px; padding:14px; }
    .stat .kpi{ font-size: 22px; font-weight: 700; }
    .stat .label{ color: var(--muted); font-size: 12px; letter-spacing: .3px; text-transform: uppercase; }

    .progress{
      position: relative; background: #0c132b; border:1px solid var(--border); border-radius: 10px; height: 12px; overflow: hidden; margin-top: 8px;
    }
    .progress > span{ position:absolute; inset:0; width: var(--value, 40%); background: linear-gradient(90deg, var(--success), var(--accent)); }

    /* Filters */
    .filters{ display:flex; gap:10px; flex-wrap: wrap; }
    .chip{
      display:inline-flex; align-items:center; gap:8px; padding:8px 12px; border-radius: 999px;
      background: var(--badge-bg); border:1px solid var(--border); color: var(--text); font-weight: 600; font-size: 13px;
    }
    .chip .dot{ width:8px; height:8px; border-radius:999px; background: var(--muted);}
    .chip.success .dot{ background: var(--success); }
    .chip.warning .dot{ background: var(--warning); }
    .chip.danger .dot{ background: var(--danger); }
    .chip.info .dot{ background: var(--primary); }

    /* Main */
    main{ margin: 22px 0 40px; display: grid; grid-template-columns: 1fr 320px; gap: 18px; }
    @media (max-width: 1100px){ main{ grid-template-columns: 1fr; } }

    .table{
      width:100%; border-collapse: collapse; overflow:hidden; border-radius: 16px; border:1px solid var(--border);
      background: linear-gradient(180deg, rgba(255,255,255,.02), rgba(255,255,255,.01));
    }
    .table thead th{
      text-align:left; font-size: 12px; text-transform: uppercase; letter-spacing:.4px; color: var(--muted);
      padding: 14px 14px; background: #0f1737; position: sticky; top: 66px; z-index: 5;
    }
    .table tbody td{ padding: 14px; border-top:1px solid var(--border); vertical-align: top; }
    .table tr:hover td{ background: rgba(255,255,255,.02); }

    .badge{ display:inline-flex; align-items:center; gap:8px; padding:6px 10px; border-radius: 999px; font-weight:700; font-size:12px; border:1px solid; }
    .badge .dot{ width:8px; height:8px; border-radius:999px; }
    .badge.open{ color:#ffdbdb; background: rgba(239, 68, 68, .1); border-color: rgba(239, 68, 68, .35)}
    .badge.open .dot{ background: var(--danger); }
    .badge.in-progress{ color:#fff1da; background: rgba(245, 158, 11, .1); border-color: rgba(245, 158, 11, .35)}
    .badge.in-progress .dot{ background: var(--warning); }
    .badge.resolved{ color:#dafbe6; background: rgba(34, 197, 94, .1); border-color: rgba(34, 197, 94, .35)}
    .badge.resolved .dot{ background: var(--success); }

    .assignee{
      display: inline-flex; align-items: center; gap:10px; background: var(--badge-bg); padding: 6px 10px; border-radius: 999px; border:1px solid var(--border);
    }
    .avatar{ width: 24px; height: 24px; border-radius: 8px; background: linear-gradient(135deg, #7dd3fc, #60a5fa); box-shadow: inset 0 0 0 1px rgba(255,255,255,.15); }

    .actions{ display:flex; gap:8px; }
    .icon-btn{ width:36px; height:36px; border: 1px solid var(--border); background: var(--card-2); border-radius: 12px; display:grid; place-items:center; cursor:pointer }
    .icon-btn:hover{ border-color:#33406b }

    /* Sidebar */
    .sidebar .card + .card{ margin-top: 14px; }
    .sidebar h3{ margin: 0 0 10px; font-size: 14px; text-transform: uppercase; letter-spacing: .35px; color: var(--muted); }

    .form-row{ display:grid; gap:10px; grid-template-columns: 1fr 1fr; }
    .form-row.full{ grid-template-columns: 1fr; }
    .input, select, textarea{
      width:100%; padding: 12px 12px; border-radius: 12px; border:1px solid var(--border); background: #0c132b; color: var(--text);
      outline: none; box-shadow: inset 0 0 0 1px rgba(255,255,255,.02);
    }
    textarea{ min-height: 94px; resize: vertical; }
    label{ font-size: 12px; color: var(--muted); margin-bottom: 6px; display:block; }

    .hint{ color: var(--muted); font-size: 12px; }

    /* Footer */
    footer{ border-top:1px solid var(--border); color: var(--muted); font-size: 13px; padding: 16px 24px; text-align:center; }

    /* Utilities */
    .sr-only{ position:absolute; width:1px; height:1px; overflow:hidden; clip:rect(0,0,0,0); white-space:nowrap; clip-path: inset(50%); }
    .spacer{ height: 6px; }

    /* Print-friendly */
    @media print{
      body{ background: #fff; color: #000; }
      header{ position: static; backdrop-filter:none; background:#fff; }
      .table thead th{ position: static; background:#fff; }
      .card, .stat, .table { box-shadow:none; }
    }
  </style>
</head>
<body>
  <header>
    <div class="nav container" role="navigation" aria-label="Primary">
      <div class="brand" aria-label="Residency Issue Tracker home">
        <div class="logo" aria-hidden="true"></div>
        <span>Residency Issue Tracker</span>
      </div>
      <div class="nav-actions">
        <button class="btn" aria-label="Export as CSV">Export CSV</button>
        <button class="btn primary" aria-label="Create new issue">New Issue</button>
      </div>
    </div>
  </header>

  <div class="container">
    <section class="page-head">
      <div class="card">
        <div class="page-title">
          <div>
            <h1>Dashboard</h1>
            <p class="lead">Track and manage residency issues across departments, locations, and residents.</p>
          </div>
          <div class="filters" role="list" aria-label="Quick Filters">
            <div class="chip info" role="listitem"><span class="dot"></span> All</div>
            <div class="chip" role="listitem"><span class="dot"></span> Housing</div>
            <div class="chip" role="listitem"><span class="dot"></span> Stipend</div>
            <div class="chip" role="listitem"><span class="dot"></span> Rotations</div>
            <div class="chip" role="listitem"><span class="dot"></span> IT</div>
          </div>
        </div>

        <div class="stats" aria-label="Summary statistics">
          <div class="stat" aria-live="polite">
            <div class="kpi">128</div>
            <div class="label">Open</div>
            <div class="progress" aria-hidden="true" style="--value: 68%"><span></span></div>
          </div>
          <div class="stat">
            <div class="kpi">42</div>
            <div class="label">In Progress</div>
            <div class="progress" style="--value: 40%"><span></span></div>
          </div>
          <div class="stat">
            <div class="kpi">301</div>
            <div class="label">Resolved</div>
            <div class="progress" style="--value: 80%"><span></span></div>
          </div>
          <div class="stat">
            <div class="kpi">2.4d</div>
            <div class="label">Avg. Response</div>
            <div class="progress" style="--value: 55%"><span></span></div>
          </div>
        </div>
      </div>

      <aside class="card" aria-labelledby="filters-heading">
        <h2 id="filters-heading" class="sr-only">Filters</h2>
        <h3>Status</h3>
        <div class="filters" style="margin-bottom:12px">
          <span class="chip" role="checkbox" aria-checked="true"><span class="dot"></span> Open</span>
          <span class="chip" role="checkbox" aria-checked="true"><span class="dot"></span> In Progress</span>
          <span class="chip" role="checkbox" aria-checked="false"><span class="dot"></span> Resolved</span>
        </div>
        <h3>Priority</h3>
        <div class="filters">
          <span class="chip danger"><span class="dot"></span> High</span>
          <span class="chip warning"><span class="dot"></span> Medium</span>
          <span class="chip success"><span class="dot"></span> Low</span>
        </div>
      </aside>
    </section>

    <main>
      <section aria-labelledby="issues-heading">
        <h2 id="issues-heading" class="sr-only">Issues</h2>
        <table class="table" role="table">
          <thead>
            <tr>
              <th scope="col">Issue</th>
              <th scope="col">Resident</th>
              <th scope="col">Category</th>
              <th scope="col">Priority</th>
              <th scope="col">Status</th>
              <th scope="col">Assignee</th>
              <th scope="col">Updated</th>
              <th scope="col" style="width:86px">Actions</th>
            </tr>
          </thead>
          <tbody>
            <!-- Row 1 -->
            <tr>
              <td>
                <strong>On‑call room AC not working</strong>
                <div class="hint">Tower B · 7th floor</div>
              </td>
              <td>
                <div class="assignee" aria-label="Reporter: Alex Kim">
                  <div class="avatar" aria-hidden="true"></div>
                  Alex Kim
                </div>
              </td>
              <td>Facilities</td>
              <td><span class="badge in-progress"><span class="dot"></span> Medium</span></td>
              <td><span class="badge open"><span class="dot"></span> Open</span></td>
              <td>
                <div class="assignee">
                  <div class="avatar" aria-hidden="true" style="background: linear-gradient(135deg,#a7f3d0,#34d399)"></div>
                  Priya S
                </div>
              </td>
              <td>Aug 20, 2025</td>
              <td class="actions">
                <button class="icon-btn" title="View" aria-label="View">🔍</button>
                <button class="icon-btn" title="Edit" aria-label="Edit">✏️</button>
              </td>
            </tr>
            <!-- Row 2 -->
            <tr>
              <td>
                <strong>Stipend not credited for July</strong>
                <div class="hint">Finance ticket #5831</div>
              </td>
              <td>
                <div class="assignee" aria-label="Reporter: Riya Patel">
                  <div class="avatar" aria-hidden="true" style="background: linear-gradient(135deg,#fbcfe8,#f472b6)"></div>
                  Riya Patel
                </div>
              </td>
              <td>Finance</td>
              <td><span class="badge open"><span class="dot"></span> High</span></td>
              <td><span class="badge in-progress"><span class="dot"></span> In Progress</span></td>
              <td>
                <div class="assignee">
                  <div class="avatar" aria-hidden="true" style="background: linear-gradient(135deg,#fde68a,#f59e0b)"></div>
                  Finance Team
                </div>
              </td>
              <td>Aug 22, 2025</td>
              <td class="actions">
                <button class="icon-btn" title="View" aria-label="View">🔍</button>
                <button class="icon-btn" title="Edit" aria-label="Edit">✏️</button>
              </td>
            </tr>
            <!-- Row 3 -->
            <tr>
              <td>
                <strong>Rotation schedule mismatch</strong>
                <div class="hint">Surgery vs. Medicine swap</div>
              </td>
              <td>
                <div class="assignee" aria-label="Reporter: Omar Khan">
                  <div class="avatar" aria-hidden="true" style="background: linear-gradient(135deg,#c7d2fe,#818cf8)"></div>
                  Omar Khan
                </div>
              </td>
              <td>Academics</td>
              <td><span class="badge in-progress"><span class="dot"></span> Medium</span></td>
              <td><span class="badge open"><span class="dot"></span> Open</span></td>
              <td>
                <div class="assignee">
                  <div class="avatar" aria-hidden="true" style="background: linear-gradient(135deg,#bbf7d0,#22c55e)"></div>
                  Dr. Mehta
                </div>
              </td>
              <td>Aug 18, 2025</td>
              <td class="actions">
                <button class="icon-btn" title="View" aria-label="View">🔍</button>
                <button class="icon-btn" title="Edit" aria-label="Edit">✏️</button>
              </td>
            </tr>
            <!-- Row 4 -->
            <tr>
              <td>
                <strong>EMR login locked</strong>
                <div class="hint">Too many failed attempts</div>
              </td>
              <td>
                <div class="assignee" aria-label="Reporter: Sara Chen">
                  <div class="avatar" aria-hidden="true" style="background: linear-gradient(135deg,#bae6fd,#38bdf8)"></div>
                  Sara Chen
                </div>
              </td>
              <td>IT</td>
              <td><span class="badge open"><span class="dot"></span> High</span></td>
              <td><span class="badge resolved"><span class="dot"></span> Resolved</span></td>
              <td>
                <div class="assignee">
                  <div class="avatar" aria-hidden="true" style="background: linear-gradient(135deg,#93c5fd,#3b82f6)"></div>
                  Helpdesk
                </div>
              </td>
              <td>Aug 16, 2025</td>
              <td class="actions">
                <button class="icon-btn" title="View" aria-label="View">🔍</button>
                <button class="icon-btn" title="Edit" aria-label="Edit">✏️</button>
              </td>
            </tr>
          </tbody>
        </table>
      </section>

      <aside class="sidebar" aria-labelledby="new-issue-heading">
        <div class="card">
          <h2 id="new-issue-heading" style="margin-top:0">Create New Issue</h2>
          <form aria-describedby="new-issue-hint" >
            <div class="form-row full">
              <label for="title">Title</label>
              <input class="input" id="title" name="title" placeholder="Short summary (e.g., 'Wi‑Fi outage in dorm')" required />
            </div>
            <div class="form-row">
              <div>
                <label for="category">Category</label>
                <select id="category" name="category">
                  <option>Facilities</option>
                  <option>Finance</option>
                  <option>IT</option>
                  <option>Academics</option>
                  <option>Other</option>
                </select>
              </div>
              <div>
                <label for="priority">Priority</label>
                <select id="priority" name="priority">
                  <option>Low</option>
                  <option selected>Medium</option>
                  <option>High</option>
                </select>
              </div>
            </div>
            <div class="form-row full">
              <label for="description">Description</label>
              <textarea id="description" name="description" placeholder="Provide details, location, and any ticket numbers…"></textarea>
            </div>
            <div class="form-row">
              <div>
                <label for="resident">Resident</label>
                <input class="input" id="resident" name="resident" placeholder="Resident name" />
              </div>
              <div>
                <label for="assignee">Assign To</label>
                <input class="input" id="assignee" name="assignee" placeholder="Person or team" />
              </div>
            </div>
            <div class="spacer"></div>
            <button class="btn primary" type="submit" aria-label="Save issue (demo only)">Save (Demo)</button>
            <p id="new-issue-hint" class="hint">This is a static HTML/CSS demo. Add JavaScript or connect a backend to make it fully functional.</p>
          </form>
        </div>

        <div class="card">
          <h3 style="margin-top:0">Tips</h3>
          <ul style="margin:0 0 0 18px; padding:0; color:var(--muted);">
            <li>Click <em>Export CSV</em> to wire up downloads.</li>
            <li>Use the <em>Filters</em> chips as checkboxes for quick scoping.</li>
            <li>Status and Priority use consistent color semantics.</li>
          </ul>
        </div>
      </aside>
    </main>
  </div>

  <footer>
    <div class="container">© 2025 Residency Issue Tracker · HTML + CSS Demo</div>
  </footer>
</body>
</html>
